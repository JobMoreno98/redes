<?php

namespace App\Http\Controllers;

use App\Http\Requests\SegmentoRequest;
use App\Models\Segmento;
use App\Models\Red;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class SegmentoController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $buscar = $request->get('buscar', '');
        $ordenar = $request->get('ordenar', 'nuevo');
        $redSeleccionada = $request->get('red_id');

        $reds = Red::where('usa_segmentos', true)
            ->orderBy('direccion_base')
            ->get();

        if ($buscar) {
            $segmentos = Segmento::search($buscar)
                ->query(fn($q) => $q->with('red')
                    ->when($redSeleccionada, fn($q) => $q->where('red_id', $redSeleccionada))
                )
                ->paginate(15);
        } else {
            $query = Segmento::with('red');

            if ($redSeleccionada) {
                $query->where('red_id', $redSeleccionada);
            }

            switch ($ordenar) {
                case 'antiguo':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'asc':
                    $query->orderBy('segmento', 'asc');
                    break;
                case 'desc':
                    $query->orderBy('segmento', 'desc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
                    break;
            }

            $segmentos = $query->paginate(15)->withQueryString();
        }

        return view('segmentos.listado', compact('segmentos', 'buscar', 'ordenar', 'reds', 'redSeleccionada'));
    }

    public function create()
    {
        $reds = Red::where('usa_segmentos', true)
            ->orderBy('direccion_base')
            ->get();

        return view('segmentos.formulario', compact('reds'));
    }

    public function store(SegmentoRequest $request)
    {
        $this->authorize('crear_segmentos'); 

        Segmento::create($request->validated());

        return redirect()
            ->route('segmentos.index')
            ->with('success', 'Segmento agregado correctamente.');
    }

    public function edit($id)
    {
        $segmento = Segmento::with('red')->findOrFail($id);

        $reds = Red::where('usa_segmentos', true)
            ->orderBy('direccion_base')
            ->get();

        return view('segmentos.editar', compact('segmento', 'reds'));
    }

    public function update(SegmentoRequest $request, $id)
    {
        $this->authorize('editar_segmentos'); 

        $segmento = Segmento::findOrFail($id);
        $segmento->update($request->validated());

        return redirect()
            ->route('segmentos.index')
            ->with('success', 'Segmento actualizado correctamente.');
    }

    public function destroy($id)
    {
        $this->authorize('eliminar_segmentos'); 

        $segmento = Segmento::findOrFail($id);
        $segmento->delete();

        return redirect()
            ->route('segmentos.index')
            ->with('success', 'Segmento eliminado correctamente.');
    }
}