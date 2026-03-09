<?php

namespace App\Http\Controllers;

use App\Http\Requests\UsuarioRequest;
use App\Models\Usuario;
use App\Models\Dependencia;
use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth; 

class UsuarioController extends Controller
{
    use AuthorizesRequests; 

    public function index(Request $request)
    {
        $buscar = $request->get('buscar', '');
        $ordenar = $request->get('ordenar', 'asc');
        $usuarioAutenticado = Auth::user();

        if ($buscar) {
            $usuarios = Usuario::search($buscar)
                ->query(function ($query) use ($usuarioAutenticado) { 
                    $query->with('dependencia', 'roles');
                    
                    if (!$usuarioAutenticado->roles()->where('nombre_rol', 'Programador')->exists()) {
                        $query->whereDoesntHave('roles', function ($q) {
                            $q->where('nombre_rol', 'Programador');
                        });
                    }
                })
                ->paginate(15);
        } else {
            $query = Usuario::with('dependencia', 'roles');

            if (!$usuarioAutenticado->roles()->where('nombre_rol', 'Programador')->exists()) {
                $query->whereDoesntHave('roles', function ($q) {
                    $q->where('nombre_rol', 'Programador');
                });
            }

            switch ($ordenar) {
                case 'antiguo': $query->orderBy('created_at', 'asc'); break;
                case 'asc': $query->orderBy('nombre', 'asc'); break;
                case 'desc': $query->orderBy('nombre', 'desc'); break;
                default: $query->orderBy('created_at', 'desc'); break;
            }
            $usuarios = $query->paginate(15)->withQueryString();
        }

        return view('usuarios.listado', [
            'usuarios' => $usuarios,
            'buscar' => $buscar,
            'ordenar' => $ordenar,
        ]);
    }

    public function create()
    {
        $dependencias = Dependencia::orderBy('nombre')->get();

        $rolesQuery = Rol::orderBy('nombre_rol');
        if (!Auth::user()->roles()->where('nombre_rol', 'Programador')->exists()) {
            $rolesQuery->where('nombre_rol', '!=', 'Programador');
        }
        $roles = $rolesQuery->get();

        return view('usuarios.formulario', compact('dependencias', 'roles'));
    }

    public function store(UsuarioRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        $usuario = Usuario::create($data);

        $usuario->roles()->sync($request->input('roles', []));

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario agregado y roles asignados.');
    }

    public function edit($id)
    {
        $usuario = Usuario::findOrFail($id);

        // Si el usuario que se intenta editar es "Programador" y el admin no es "Programador"
        if ($usuario->roles()->where('nombre_rol', 'Programador')->exists() && 
            !Auth::user()->roles()->where('nombre_rol', 'Programador')->exists()) 
        {
            abort(403, 'No tiene permisos para editar a este usuario.');
        }

        $dependencias = Dependencia::orderBy('nombre')->get();

        $rolesQuery = Rol::orderBy('nombre_rol');
        if (!Auth::user()->roles()->where('nombre_rol', 'Programador')->exists()) {
            $rolesQuery->where('nombre_rol', '!=', 'Programador');
        }
        $roles = $rolesQuery->get();

        return view('usuarios.editar', compact('usuario', 'dependencias', 'roles'));
    }

    public function update(UsuarioRequest $request, $id)
    {
        $usuario = Usuario::findOrFail($id);

        if ($usuario->roles()->where('nombre_rol', 'Programador')->exists() && 
            !Auth::user()->roles()->where('nombre_rol', 'Programador')->exists()) 
        {
            abort(403, 'No tiene permisos para actualizar a este usuario.');
        }

        $data = $request->validated();

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $usuario->update($data);

        $usuario->roles()->sync($request->input('roles', []));

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario actualizado y roles asignados.');
    }

    public function destroy($id)
    {
        $this->authorize('eliminar_usuarios');

        $usuario = Usuario::findOrFail($id);

        if ($usuario->roles()->where('nombre_rol', 'Programador')->exists()) {
            abort(403, 'El usuario Programador no puede ser eliminado.');
        }
        $usuario->delete();

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario eliminado.');
    }
}