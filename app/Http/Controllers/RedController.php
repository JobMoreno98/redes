<?php

namespace App\Http\Controllers;

use App\Http\Requests\RedRequest;
use App\Models\Red;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class RedController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $buscar = $request->get('buscar', '');
        $ordenar = $request->get('ordenar', 'nuevo');

        if ($buscar) {
            $redes = Red::search($buscar)->paginate(15);
        } else {
            $query = Red::query();

            switch ($ordenar) {
                case 'antiguo':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'asc':
                    $query->orderBy('direccion_base', 'asc');
                    break;
                case 'desc':
                    $query->orderBy('direccion_base', 'desc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
                    break;
            }

            $redes = $query->paginate(15)->withQueryString();
        }

        return view('redes.listado', compact('redes', 'buscar', 'ordenar'));
    }

    public function create()
    {
        return view('redes.formulario');
    }

    private function procesarHostsReservados(array $data): array
    {
        if (isset($data['hosts_reservados']) && is_string($data['hosts_reservados'])) {
            $hosts = array_map(
                'intval', // Convertir a entero
                array_filter( 
                    array_map('trim', explode(',', $data['hosts_reservados'])) // Separar por coma y quitar espacios
                )
            );
            
            $data['hosts_reservados'] = array_unique(
                array_filter($hosts, fn($h) => $h >= 1 && $h <= 255)
            );
        } else {
            $data['hosts_reservados'] = [];
        }
        
        return $data;
    }

    public function store(RedRequest $request)
    {
        $this->authorize('crear_redes');

        $data = $request->validated();
        $data = $this->procesarHostsReservados($data);

        Red::create($data); 

        return redirect()
            ->route('redes.index')
            ->with('success', 'Red registrada correctamente.');
    }


    public function edit($id)
    {
        $red = Red::findOrFail($id);
        return view('redes.editar', compact('red'));
    }

    public function update(RedRequest $request, $id)
    {
        $this->authorize('editar_redes');

        $red = Red::findOrFail($id);
        $data = $request->validated();
        $data = $this->procesarHostsReservados($data);

        $red->update($data); 

        return redirect()
            ->route('redes.index')
            ->with('success', 'Red actualizada correctamente.');
    }

    public function destroy($id)
    {
        $this->authorize('eliminar_redes'); 

        $red = Red::findOrFail($id);
        $red->delete();

        return redirect()
            ->route('redes.index')
            ->with('success', 'Red eliminada correctamente.');
    }
}