<?php

namespace App\Http\Controllers;

use App\Models\Permiso;
use App\Http\Requests\PermisoRequest;
use Illuminate\Http\Request;

class PermisoController extends Controller
{

    public function index(Request $request)
    {
        $buscar = $request->get('buscar', '');
        $ordenar = $request->get('ordenar', 'nuevo');

        if ($buscar) {
            $query = Permiso::where('nombre_permiso', 'LIKE', "%{$buscar}%");
        } else {
            $query = Permiso::query();

            switch ($ordenar) {
                case 'antiguo': $query->orderBy('created_at', 'asc'); break;
                case 'asc': $query->orderBy('nombre_permiso', 'asc'); break;
                case 'desc': $query->orderBy('nombre_permiso', 'desc'); break;
                default: $query->orderBy('created_at', 'desc'); break; // 'nuevo'
            }
        }
        
        $permisos = $query->paginate(15)->withQueryString();

        return view('permisos.listado', compact('permisos', 'buscar', 'ordenar'));
    }

}