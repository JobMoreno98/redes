<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegistroRequest;
use App\Models\Registro;
use App\Models\Dependencia;
use App\Models\Segmento;
use App\Models\Red;
use App\Models\Dispositivo;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class RegistroController extends Controller
{
    use AuthorizesRequests;

    public function create()
    {
        return view('registros.formulario', [
            'dependencias' => Dependencia::orderBy('nombre')->get(),
            'segmentos' => Segmento::orderBy('segmento')->get(),
            'redes' => Red::orderBy('direccion_base')->get(),
            'dispositivos' => Dispositivo::orderBy('tipo_dispositivo')->get(),
        ]);
    }

    public function usar(Request $request)
    {
        $ip = $request->get('ip');
        $segmentoId = $request->get('segmento_id');
        $redId = $request->get('red_id');
        $dispositivoId = $request->get('tipo_dispositivo_id');

        return view('registros.formulario', [
            'dependencias' => Dependencia::orderBy('nombre')->get(),
            'segmentos' => Segmento::orderBy('segmento')->get(),
            'redes' => Red::orderBy('direccion_base')->get(),
            'dispositivos' => Dispositivo::orderBy('tipo_dispositivo')->get(),
            'ipSeleccionada' => $ip,
            'segmentoSeleccionado' => $segmentoId,
            'redSeleccionada' => $redId,
            'dispositivoSeleccionado' => $dispositivoId,
        ]);
    }

    public function store(RegistroRequest $request)
    {
        $this->authorize('crear_registros'); 

        $validated = $request->validated();

        Registro::onlyTrashed()
            ->where(function ($query) use ($validated) {
                $query->where('ip', $validated['ip']); // Si la IP existe en la papelera
                if (!empty ($validated['mac'])){
                    $query->orWhere('mac', $validated['mac']);
                } 

                if (!empty ($validated['numero_serie'])) {
                    $query->orWhere('numero_serie', $validated['numero_serie']);
                };
            })
            ->forceDelete();

        Registro::create([
            'tipo_dispositivo_id' => $validated['tipo_dispositivo_id'],
            'red_id' => $validated['red_id'],
            'segmento_id' => $validated['segmento_id'] ?? null,
            'dependencia_id' => $validated['dependencia_id'],
            'ip' => $validated['ip'],
            'mac' => $validated['mac'] ?? null,
            'numero_serie' => $validated['numero_serie'] ?? null,
            'descripcion' => $validated['descripcion'] ?? null,
            'responsable' => $validated['responsable'],
        ]);

        return redirect()->route('registros.ocupadas')
            ->with('success', 'Registro guardado correctamente.');
    }


    public function buscar()
    {
        return view('registros.buscar');
    }

    public function ocupadas(Request $request)
    {
        $redes = Red::orderBy('direccion_base')->get();
        // $dispositivos = Dispositivo::orderBy('tipo_dispositivo')->get(); //

        $redSeleccionada = $request->input('red_id');
        $segmentoSeleccionado = $request->input('segmento_id');
        $dispositivoSeleccionado = $request->input('tipo_dispositivo_id');
        $ordenar = $request->input('ordenar', 'nuevo');
        
        $buscar = $request->input('buscar', '');

        $red = $redSeleccionada ? Red::find($redSeleccionada) : null;

        
        // La lista de dispositivos depende de la red seleccionada
        if ($red) {
            $dispositivos = $red->dispositivos()->orderBy('tipo_dispositivo')->get();
        } else {
            //si no hay red, muestra todos
            $dispositivos = Dispositivo::orderBy('tipo_dispositivo')->get();
        }

        $segmentos = ($red && $red->usa_segmentos)
            ? $red->segmentos()->orderBy('segmento')->get()
            : collect();

        $query = Registro::with(['red', 'segmento', 'tipo_dispositivo', 'dependencia']);

        if (!empty($buscar)) {
            $isIp = filter_var($buscar, FILTER_VALIDATE_IP);
            $isMac = preg_match('/^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$/i', $buscar);

            if ($isIp) {
                $query->where('ip', $buscar);
            } elseif ($isMac) {
                $query->where('mac', $buscar);
            } else {
                $ids = Registro::search($buscar)->keys(); 
                
                $query->where(function ($q) use ($buscar, $ids) {
                    $q->whereIn('id', $ids)
                        ->orWhere('numero_serie', $buscar);
                });
            }
        }

        if ($red) {
            if ($red->usa_segmentos) {
                $query->where('red_id', $red->id);
            } else {
                $base = rtrim($red->direccion_base, '.');
                $query->where('ip', 'like', "{$base}.%");
            }
        }

        if ($segmentoSeleccionado) {
            $query->where('segmento_id', $segmentoSeleccionado);
        }

        if ($dispositivoSeleccionado) {
            $query->where('tipo_dispositivo_id', $dispositivoSeleccionado);
        }

        switch ($ordenar) {
            case 'antiguo':
                $query->orderBy('created_at', 'asc');
                break;
            case 'asc':
                $query->orderBy('ip', 'asc');
                break;
            case 'desc':
                $query->orderBy('ip', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        $registros = $query->paginate(15)->withQueryString();

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
        $redId = $request->get('red_id');
        $segmentoId = $request->get('segmento_id'); 

        $red = $redId ? Red::find($redId) : Red::orderBy('direccion_base')->first();
        if (!$red) {
            return redirect()->route('redes.index')
                ->with('error', 'No hay redes registradas.');
        }

        $segmento = null;
        $errorFaltanSegmentos = false; 

        if ($red->usa_segmentos) {
            
            if ($segmentoId) {
                $segmento = Segmento::where('id', $segmentoId)
                                    ->where('red_id', $red->id)
                                    ->first();
            }

            if (!$segmento) {
                $segmento = Segmento::where('red_id', $red->id)->orderBy('segmento')->first();
            }

            if (!$segmento) {
                $errorFaltanSegmentos = true;
            }
        }

        $disponibles = collect(); //colección vacia

        // Solo calcula si existen los datos necesarios:
        // (Si la red no usa segmentos) O (Si usa segmentos y tenemos un segmento válido)
        if (!$red->usa_segmentos || ($red->usa_segmentos && $segmento)) 
        {
            $prefix = $red->usa_segmentos
                ? "{$red->direccion_base}.{$segmento->segmento}."
                : "{$red->direccion_base}.";

            $reservados = $red->hosts_reservados ?? [];

            $todas = collect(range(1, 255))
                ->reject(fn($i) => in_array($i, $reservados)) 
                ->map(fn($i) => "{$prefix}{$i}");
            
            $ocupadas = Registro::where('ip', 'like', "{$prefix}%")->pluck('ip');
            $disponibles = $todas->diff($ocupadas)->values();
        }

        $dispositivosDeRed = $red->dispositivos()->orderBy('tipo_dispositivo')->get();
        $segmentosDeRed = $red->usa_segmentos
            ? Segmento::where('red_id', $red->id)->orderBy('segmento')->get()
            : collect();

        return view('registros.disponibles', [
            'redes' => Red::orderBy('direccion_base')->get(),
            'segmentos' => $segmentosDeRed,
            'redSeleccionada' => $red->id,
            'segmentoSeleccionado' => $segmento->id ?? null, 
            'red' => $red,
            'segmento' => $segmento, 
            'disponibles' => $disponibles,
            'dispositivosDeRed' => $dispositivosDeRed,
            'errorFaltanSegmentos' => $errorFaltanSegmentos,
        ]);
    }
    public function edit($id)
    {
        $registro = Registro::findOrFail($id);

        return view('registros.editar', [
            'registro' => $registro,
            'dependencias' => Dependencia::orderBy('nombre')->get(),
            'segmentos' => Segmento::orderBy('segmento')->get(),
            'redes' => Red::orderBy('direccion_base')->get(),
            'dispositivos' => Dispositivo::orderBy('tipo_dispositivo')->get(),
        ]);
    }

    public function update(RegistroRequest $request, $id)
    {
        $this->authorize('editar_registros');

        $validated = $request->validated();
        $registro = Registro::findOrFail($id);

        $registro->update([
            'tipo_dispositivo_id' => $validated['tipo_dispositivo_id'],
            'red_id' => $validated['red_id'],
            'segmento_id' => $validated['segmento_id'] ?? null,
            'dependencia_id' => $validated['dependencia_id'],
            'ip' => $validated['ip'],
            'mac' => $validated['mac'] ?? null,
            'numero_serie' => $validated['numero_serie'] ?? null,
            'descripcion' => $validated['descripcion'] ?? null,
            'responsable' => $validated['responsable'],
        ]);

        return redirect()->route('registros.ocupadas')
            ->with('success', 'Registro actualizado correctamente.');
    }

    public function modificar(Request $request)
    {
        $request->validate(['ip' => 'required|ipv4']);
        $registro = Registro::where('ip', $request->ip)->first();

        if (!$registro) {
            return back()->withErrors(['ip' => 'No se encontró ningún registro con esa IP.']);
        }

        return redirect()->route('registros.edit', $registro->id);
    }

    public function eliminar(Request $request)
    {
        $request->validate(['ip' => 'required|ipv4']);
        $registro = Registro::where('ip', $request->ip)->first();

        if (!$registro) {
            return response()->json(['error' => 'No se encontró ningún registro con esa IP.'], 404);
        }

        return response()->json([
            'id' => $registro->id,
            'ip' => $registro->ip,
            'mac' => $registro->mac ?? 'Pendiente',
            'descripcion' => $registro->descripcion ?? '—',
            'dependencia' => $registro->dependencia->nombre ?? 'N/A',
            'tipo' => $registro->tipo_dispositivo->tipo_dispositivo ?? 'N/A',
            'responsable' => $registro->responsable ?? 'N/A',
        ]);
    }

    public function destroy($id)
    {
        $this->authorize('eliminar_registros');

        $registro = Registro::findOrFail($id);
        $registro->delete();

        return redirect()->route('registros.ocupadas')->with('success', 'El registro fue eliminado correctamente.');
    }

    public function eliminadas(Request $request)
    {
        $buscar = $request->input('buscar', '');
        $ordenar = $request->input('ordenar', 'nuevo');

        $query = Registro::onlyTrashed()
            ->with(['red', 'segmento', 'tipo_dispositivo', 'dependencia']);

        if (!empty($buscar)) {
            $isIp = filter_var($buscar, FILTER_VALIDATE_IP);
            $isMac = preg_match('/^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$/i', $buscar);

            if ($isIp) {
                $query->where('ip', $buscar);
            } elseif ($isMac) {
                $query->where('mac', $buscar);
            } else {
                $ids = Registro::search($buscar)->withTrashed()->keys();
                
                $query->where(function ($q) use ($buscar, $ids) {
                    $q->whereIn('id', $ids)
                        ->orWhere('numero_serie', $buscar);
                });
            }
        }

        switch ($ordenar) {
            case 'antiguo':
                $query->orderBy('created_at', 'asc');
                break;
            case 'asc':
                $query->orderBy('ip', 'asc');
                break;
            case 'desc':
                $query->orderBy('ip', 'desc');
                break;
            default:
                $query->orderBy('deleted_at', 'desc');
        }

        $registros = $query->paginate(15)->withQueryString();

        return view('registros.eliminadas', [
            'registros' => $registros,
            'buscar' => $buscar,
            'ordenar' => $ordenar,
        ]);
    }

    public function restore($id)
    {
        $this->authorize('restaurar_registros_eliminados'); 

        $registro = Registro::onlyTrashed()->findOrFail($id);
        $registro->restore();

        return redirect()->route('registros.eliminadas')
            ->with('success', 'Registro restaurado correctamente.');
    }
    public function forceDestroy($id)
    {
        $this->authorize('eliminar_registros_eliminados'); 

        $registro = Registro::onlyTrashed()->findOrFail($id);
        $registro->forceDelete();

        return redirect()->route('registros.eliminadas')
            ->with('success', 'Registro eliminado permanentemente.');
    }
}