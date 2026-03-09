<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use App\Models\Permiso;
use App\Http\Requests\RolRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;

class RolController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $buscar = $request->get('buscar', '');
        $ordenar = $request->get('ordenar', 'nuevo');

        if ($buscar) {
            $query = Rol::where('nombre_rol', 'LIKE', "%{$buscar}%");
        } else {
            $query = Rol::query();
        }

        // Oculta el rol 'Programador' de la lista para todos,
        // excepto para el Programador.
        if (!Auth::user()->roles()->where('nombre_rol', 'Programador')->exists()) {
            $query->where('nombre_rol', '!=', 'Programador');
        }

        switch ($ordenar) {
            case 'antiguo': $query->orderBy('created_at', 'asc'); break;
            case 'asc': $query->orderBy('nombre_rol', 'asc'); break;
            case 'desc': $query->orderBy('nombre_rol', 'desc'); break;
            default: $query->orderBy('created_at', 'desc'); break;
        }
        
        $roles = $query->with('permisos')->paginate(15)->withQueryString();

        return view('roles.listado', compact('roles', 'buscar', 'ordenar'));
    }

    public function create()
    {
        $permisos = Permiso::orderBy('nombre_permiso')->get();
        
        $permisosAgrupados = $permisos->groupBy(function ($permiso) {
            $groupName = explode('_', $permiso->nombre_permiso, 2)[1] ?? $permiso->nombre_permiso;
            return Str::ucfirst(Str::replace('_', ' ', $groupName));
        });
        
        return view('roles.formulario', compact('permisosAgrupados'));
    }

    public function store(RolRequest $request)
    {
        $this->authorize('crear_roles');

        $data = $request->validated();

        $rol = Rol::create([
            'nombre_rol' => $data['nombre_rol']
        ]);

        $rol->permisos()->attach($data['permisos']);

        return redirect()->route('roles.index')
            ->with('success', 'Rol agregado correctamente.');
    }

    public function edit($id)
    {
        $rol = Rol::findOrFail($id);

        // Bloquea la edición del rol Programador
        if ($rol->nombre_rol === 'Programador') {
            abort(403, 'Este rol no puede ser modificado.');
        }

        $permisos = Permiso::orderBy('nombre_permiso')->get();

        $permisosAgrupados = $permisos->groupBy(function ($permiso) {
            $groupName = explode('_', $permiso->nombre_permiso, 2)[1] ?? $permiso->nombre_permiso;
            return Str::ucfirst(Str::replace('_', ' ', $groupName));
        });

        return view('roles.editar', compact('rol', 'permisosAgrupados'));
    }

    public function update(RolRequest $request, $id)
    {
        $rol = Rol::findOrFail($id);

        // Bloquea la actualización del rol Programador
        if ($rol->nombre_rol === 'Programador') {
            abort(403, 'Este rol no puede ser modificado.');
        }
        
        $this->authorize('editar_roles');
        
        $data = $request->validated();

        $rol->update([
            'nombre_rol' => $data['nombre_rol']
        ]);

        $rol->permisos()->sync($data['permisos']);

        return redirect()->route('roles.index')
            ->with('success', 'Rol actualizado correctamente.');
    }

    public function destroy($id)
    {
        $rol = Rol::findOrFail($id);

        // Bloquea la eliminación del rol Programador
        if ($rol->nombre_rol === 'Programador') {
            abort(403, 'Este rol no puede ser eliminado.');
        }

        $this->authorize('eliminar_roles');

        $rol->delete();

        return redirect()->route('roles.index')
            ->with('success', 'Rol eliminado correctamente.');
    }
}