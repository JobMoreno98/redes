<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegistroRequest;
use App\Models\Dependencia;
use App\Models\Dispositivo;
use App\Models\Red;
use App\Models\Registro;
use App\Models\Segmento;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RegistroController extends Controller
{
    use AuthorizesRequests;

    private const POR_PAGINA = 15;

    /* ---------------------------------------------------------------
     |  Helpers privados
     * ------------------------------------------------------------- */

    /** Catálogos usados por los formularios de alta y edición. */
    private function catalogos(): array
    {
        return [
            'dependencias' => Dependencia::orderBy('nombre')->get(),
            'segmentos'    => Segmento::orderBy('segmento')->get(),
            'redes'        => Red::orderBy('direccion_base')->get(),
            'dispositivos' => Dispositivo::orderBy('tipo_dispositivo')->get(),
        ];
    }

    /** Campos del registro a partir de los datos validados (compartido por store y update). */
    private function datosRegistro(array $validated): array
    {
        return [
            'tipo_dispositivo_id' => $validated['tipo_dispositivo_id'],
            'red_id'              => $validated['red_id'],
            'segmento_id'         => $validated['segmento_id'] ?? null,
            'dependencia_id'      => $validated['dependencia_id'],
            'ip'                  => $validated['ip'],
            'mac'                 => $validated['mac'] ?? null,
            'numero_serie'        => $validated['numero_serie'] ?? null,
            'descripcion'         => $validated['descripcion'] ?? null,
            'responsable'         => $validated['responsable'],
        ];
    }

    /* ---------------------------------------------------------------
     |  Alta
     * ------------------------------------------------------------- */

    public function create()
    {
        return view('registros.formulario', $this->catalogos());
    }

    public function usar(Request $request)
    {
        return view('registros.formulario', $this->catalogos() + [
            'ipSeleccionada'          => $request->input('ip'),
            'segmentoSeleccionado'    => $request->input('segmento_id'),
            'redSeleccionada'         => $request->input('red_id'),
            'dispositivoSeleccionado' => $request->input('tipo_dispositivo_id'),
        ]);
    }

    public function store(RegistroRequest $request)
    {
        $this->authorize('crear_registros');

        $validated = $request->validated();

        // Si la papelera contiene la misma IP / MAC / serie, se elimina definitivamente
        // para liberar los valores únicos. Todo en una transacción: si el create falla,
        // no se pierde lo que había en la papelera.
        DB::transaction(function () use ($validated) {
            Registro::onlyTrashed()
                ->where(function ($query) use ($validated) {
                    $query->where('ip', $validated['ip'])
                        ->when(
                            $validated['mac'] ?? null,
                            fn ($q, $mac) => $q->orWhere('mac', $mac)
                        )
                        ->when(
                            $validated['numero_serie'] ?? null,
                            fn ($q, $serie) => $q->orWhere('numero_serie', $serie)
                        );
                })
                ->forceDelete();

            Registro::create($this->datosRegistro($validated));
        });

        return redirect()->route('registros.ocupadas')
            ->with('success', 'Registro guardado correctamente.');
    }

    /* ---------------------------------------------------------------
     |  Consultas
     * ------------------------------------------------------------- */

    public function buscar()
    {
        return view('registros.buscar');
    }

    public function ocupadas(Request $request)
    {
        $redSeleccionada         = $request->input('red_id');
        $segmentoSeleccionado    = $request->input('segmento_id');
        $dispositivoSeleccionado = $request->input('tipo_dispositivo_id');
        $ordenar                 = $request->input('ordenar', 'asc');
        $buscar                  = $request->input('buscar', '');

        $redes = Red::orderBy('direccion_base')->get();
        $red   = $redSeleccionada ? $redes->firstWhere('id', $redSeleccionada) : null;

        // La lista de dispositivos depende de la red seleccionada (si no hay, todos)
        $dispositivos = $red
            ? $red->dispositivos()->orderBy('tipo_dispositivo')->get()
            : Dispositivo::orderBy('tipo_dispositivo')->get();

        $segmentos = $red?->usa_segmentos
            ? $red->segmentos()->orderBy('segmento')->get()
            : collect();

        $registros = Registro::with(['red', 'segmento', 'tipo_dispositivo', 'dependencia'])
            ->buscar($buscar)
            ->when($red?->usa_segmentos, fn ($q) => $q->where('red_id', $red->id))
            ->when(
                $red && !$red->usa_segmentos,
                fn ($q) => $q->where('ip', 'like', rtrim($red->direccion_base, '.') . '.%')
            )
            ->when($segmentoSeleccionado, fn ($q, $id) => $q->where('segmento_id', $id))
            ->when($dispositivoSeleccionado, fn ($q, $id) => $q->where('tipo_dispositivo_id', $id))
            ->ordenarPor($ordenar)
            ->paginate(self::POR_PAGINA)
            ->withQueryString();

        return view('registros.ocupadas', compact(
            'registros',
            'redes',
            'red',
            'segmentos',
            'dispositivos',
            'redSeleccionada',
            'segmentoSeleccionado',
            'dispositivoSeleccionado',
            'ordenar',
            'buscar'
        ));
    }

    public function disponibles(Request $request)
    {
        $redes = Red::orderBy('direccion_base')->get();

        $red = $request->filled('red_id')
            ? $redes->firstWhere('id', $request->input('red_id'))
            : null;
        $red ??= $redes->first();

        if (!$red) {
            return redirect()->route('redes.index')
                ->with('error', 'No hay redes registradas.');
        }

        // Los segmentos se cargan una sola vez y se reutilizan
        $segmentos = $red->usa_segmentos
            ? Segmento::where('red_id', $red->id)->orderBy('segmento')->get()
            : collect();

        $segmento = $red->usa_segmentos
            ? ($segmentos->firstWhere('id', $request->input('segmento_id')) ?? $segmentos->first())
            : null;

        $errorFaltanSegmentos = $red->usa_segmentos && !$segmento;

        $disponibles = collect();

        if (!$errorFaltanSegmentos) {
            $prefix = $segmento
                ? "{$red->direccion_base}.{$segmento->segmento}."
                : "{$red->direccion_base}.";

            $reservados = array_flip($red->hosts_reservados ?? []);

            // Búsquedas O(1) en lugar de Collection::diff (O(n*m))
            $ocupadas = Registro::where('ip', 'like', "{$prefix}%")
                ->pluck('ip')
                ->flip()
                ->all();

            // 1..254: el .0 es la red y el .255 el broadcast.
            // Si en tu caso necesitas el 255, cambia el rango.
            $disponibles = collect(range(1, 254))
                ->reject(fn ($i) => isset($reservados[$i]) || isset($ocupadas["{$prefix}{$i}"]))
                ->map(fn ($i) => "{$prefix}{$i}")
                ->values();
        }

        return view('registros.disponibles', [
            'redes'                => $redes,
            'segmentos'            => $segmentos,
            'redSeleccionada'      => $red->id,
            'segmentoSeleccionado' => $segmento?->id,
            'red'                  => $red,
            'segmento'             => $segmento,
            'disponibles'          => $disponibles,
            'dispositivosDeRed'    => $red->dispositivos()->orderBy('tipo_dispositivo')->get(),
            'errorFaltanSegmentos' => $errorFaltanSegmentos,
        ]);
    }

    /* ---------------------------------------------------------------
     |  Edición
     * ------------------------------------------------------------- */

    public function edit($id)
    {
        $this->authorize('editar_registros');

        return view('registros.editar', $this->catalogos() + [
            'registro' => Registro::findOrFail($id),
        ]);
    }

    public function update(RegistroRequest $request, $id)
    {
        $this->authorize('editar_registros');

        Registro::findOrFail($id)->update($this->datosRegistro($request->validated()));

        return redirect()->route('registros.ocupadas')
            ->with('success', 'Registro actualizado correctamente.');
    }

    public function modificar(Request $request)
    {
        $this->authorize('editar_registros');

        $ip = $request->validate(['ip' => 'required|ipv4'])['ip'];

        $registro = Registro::where('ip', $ip)->first();

        if (!$registro) {
            return back()->withErrors(['ip' => 'No se encontró ningún registro con esa IP.']);
        }

        return redirect()->route('registros.edit', $registro->id);
    }

    /* ---------------------------------------------------------------
     |  Eliminación (papelera)
     * ------------------------------------------------------------- */

    /**
     * Busca un registro por IP y devuelve sus datos en JSON
     * (se usa para confirmar antes de eliminar; no elimina nada).
     */
    public function eliminar(Request $request)
    {
        $this->authorize('eliminar_registros');

        $ip = $request->validate(['ip' => 'required|ipv4'])['ip'];

        $registro = Registro::with('tipo_dispositivo')->where('ip', $ip)->first();

        if (!$registro) {
            return response()->json(['error' => 'No se encontró ningún registro con esa IP.'], 404);
        }

        return response()->json([
            'id'             => $registro->id,
            'ip'             => $registro->ip,
            'mac'            => $registro->mac ?? 'Pendiente',
            'descripcion'    => $registro->descripcion ?? '—',
            'dependencia_id' => $registro->dependencia_id ?? 'N/A',
            'tipo'           => $registro->tipo_dispositivo->tipo_dispositivo ?? 'N/A',
            'responsable'    => $registro->responsable ?? 'N/A',
        ]);
    }

    public function destroy($id)
    {
        $this->authorize('eliminar_registros');

        Registro::findOrFail($id)->delete();

        return redirect()->route('registros.ocupadas')
            ->with('success', 'El registro fue eliminado correctamente.');
    }

    public function eliminadas(Request $request)
    {
        $buscar  = $request->input('buscar', '');
        $ordenar = $request->input('ordenar', 'nuevo');

        $registros = Registro::onlyTrashed()
            ->with(['red', 'segmento', 'tipo_dispositivo', 'dependencia'])
            ->buscar($buscar, conEliminados: true)
            ->ordenarPor($ordenar, columnaPorDefecto: 'deleted_at')
            ->paginate(self::POR_PAGINA)
            ->withQueryString();

        return view('registros.eliminadas', compact('registros', 'buscar', 'ordenar'));
    }

    public function restore($id)
    {
        $this->authorize('restaurar_registros_eliminados');

        Registro::onlyTrashed()->findOrFail($id)->restore();

        return redirect()->route('registros.eliminadas')
            ->with('success', 'Registro restaurado correctamente.');
    }

    public function forceDestroy($id)
    {
        $this->authorize('eliminar_registros_eliminados');

        Registro::onlyTrashed()->findOrFail($id)->forceDelete();

        return redirect()->route('registros.eliminadas')
            ->with('success', 'Registro eliminado permanentemente.');
    }
}