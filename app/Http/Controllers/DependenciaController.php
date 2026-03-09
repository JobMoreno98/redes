<?php

namespace App\Http\Controllers;

use App\Http\Requests\DependenciaRequest;
use App\Models\Dependencia;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class DependenciaController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $buscar = $request->get('buscar', '');
        $ordenar = $request->get('ordenar', 'nuevo');

        if ($buscar) {
            $dependencias = Dependencia::search($buscar)
                ->paginate(15);
        } else {
            $query = Dependencia::query();
            switch ($ordenar) {
                case 'antiguo':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'asc':
                    $query->orderBy('nombre', 'asc');
                    break;
                case 'desc':
                    $query->orderBy('nombre', 'desc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
                    break;
            }
            $dependencias = $query->paginate(15)->withQueryString();
        }

        return view('dependencias.listado', [
            'dependencias' => $dependencias,
            'buscar' => $buscar,
            'ordenar' => $ordenar,
        ]);
    }

    public function create()
    {
        return view('dependencias.formulario');
    }

    public function store(DependenciaRequest $request)
    {
        Dependencia::create($request->validated());

        return redirect()->route('dependencias.index')
            ->with('success', 'Dependencia agregada correctamente.');
    }

    public function edit($id)
    {
        $dependencia = Dependencia::findOrFail($id);
        return view('dependencias.editar', compact('dependencia'));
    }

    public function update(DependenciaRequest $request, $id)
    {
        $dependencia = Dependencia::findOrFail($id);
        $dependencia->update($request->validated());

        return redirect()->route('dependencias.index')
            ->with('success', 'Dependencia actualizada.');
    }

    public function destroy($id)
    {
        $this->authorize('eliminar_dependencias');

        $dependencia = Dependencia::findOrFail($id);
        $dependencia->delete();

        return redirect()->route('dependencias.index')
            ->with('success', 'Dependencia eliminada.');
    }
}