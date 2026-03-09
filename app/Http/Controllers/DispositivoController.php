<?php

namespace App\Http\Controllers;

use App\Http\Requests\DispositivoRequest;
use App\Models\Dispositivo;
use App\Models\Red;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; 

class DispositivoController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $buscar = $request->get('buscar', '');
        $ordenar = $request->get('ordenar', 'nuevo');

        if ($buscar) {
            $dispositivos = Dispositivo::search($buscar)
                ->query(fn($q) => $q->with('red')) 
                ->paginate(15);
        } else {
            $query = Dispositivo::with('red'); 

            switch ($ordenar) {
                case 'antiguo': $query->orderBy('created_at', 'asc'); break;
                case 'asc': $query->orderBy('tipo_dispositivo', 'asc'); break;
                case 'desc': $query->orderBy('tipo_dispositivo', 'desc'); break;
                default: $query->orderBy('created_at', 'desc'); break;
            }

            $dispositivos = $query->paginate(15)->withQueryString();
        }

        return view('dispositivos.listado', compact('dispositivos', 'buscar', 'ordenar'));
    }

    public function create()
    {
        $redes = Red::orderBy('direccion_base')->get();
        return view('dispositivos.formulario', compact('redes'));
    }

    public function store(DispositivoRequest $request)
    {
        $this->authorize('crear_dispositivos');

        Dispositivo::create($request->validated());

        return redirect()->route('dispositivos.index')
            ->with('success', 'Tipo de dispositivo agregado correctamente.');
    }

    public function edit($id)
    {
        $dispositivo = Dispositivo::findOrFail($id);
        $redes = Red::orderBy('direccion_base')->get();
        return view('dispositivos.editar', compact('dispositivo', 'redes'));
    }

    public function update(DispositivoRequest $request, $id)
    {
        $this->authorize('editar_dispositivos'); 

        $dispositivo = Dispositivo::findOrFail($id);
        $dispositivo->update($request->validated());

        return redirect()->route('dispositivos.index')
            ->with('success', 'Tipo de dispositivo actualizado correctamente.');
    }

    public function destroy($id)
    {
        $this->authorize('eliminar_dispositivos');

        $dispositivo = Dispositivo::findOrFail($id);
        $dispositivo->delete();

        return redirect()->route('dispositivos.index')
            ->with('success', 'Tipo de dispositivo eliminado correctamente.');
    }
}