@extends('layouts.app')

@section('title', 'Listado de Roles')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card p-4 shadow-lg">

            <div class="text-center mb-4">
                <h3 class="mb-2">
                    <i class="bi bi-person-lock"></i> Listado de Roles ({{ $roles->total() }})
                </h3>
                @if($roles->count() > 0 && $roles->lastPage() > 1)
                    <p class="small fw-semibold text-white mb-0">
                        Mostrando {{ $roles->firstItem() }} al {{ $roles->lastItem() }} de {{ $roles->total() }} resultados
                    </p>
                @endif
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-pill text-center alert-auto-hide" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close btn-close-black" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            @if($roles->count() > 0)
                <div class="d-flex justify-content-center align-items-center flex-wrap gap-3 mb-4">
                    @can('crear_roles')
                    <a href="{{ route('roles.create') }}" class="btn btn-success rounded-pill px-3">
                        <i class="bi bi-plus-circle me-1"></i> Agregar Nuevo
                    </a>
                    @endcan
                </div>

                <div class="table-responsive" style="overflow-x: auto; white-space: nowrap;">
                    <table class="table table-hover align-middle text-center mb-0 rounded-4" style="min-width: 600px;">
                        <thead class="table-primary text-dark">
                            <tr>
                                <th style="width: 10%;">#</th>
                                <th style="width: 30%;">Nombre del Rol</th>

                                @canany(['editar_roles', 'eliminar_roles'])
                                    <th style="width: 40%;">Permisos Asignados</th>
                                    <th style="width: 20%;">Acciones</th>
                                @else
                                    <th style="width: 60%;">Permisos Asignados</th>
                                @endcanany
                                {{-- FIN DE MODIFICACIÓN --}}

                            </tr>
                        </thead>
                        <tbody>
                            @foreach($roles as $rol)
                                <tr>
                                    <td>{{ $roles->firstItem() + $loop->index }}</td>
                                    <td class="text-start">{{ $rol->nombre_rol }}</td>
                                    <td>
                                        @forelse($rol->permisos->take(5) as $permiso)
                                            <span class="badge bg-primary rounded-pill">{{ $permiso->nombre_permiso }}</span>
                                        @empty
                                            <span class="badge bg-secondary rounded-pill">Sin permisos</span>
                                        @endforelse
                                        @if($rol->permisos->count() > 5)
                                            <span class="badge bg-dark rounded-pill">+ {{ $rol->permisos->count() - 5 }} más</span>
                                        @endif
                                    </td>

                                    @canany(['editar_roles', 'eliminar_roles'])
                                    <td>
                                        @if($rol->nombre_rol === 'Programador')
                                            <span class="badge bg-secondary rounded-pill">No editable</span>
                                        
                                        @else
                                            @can('editar_roles')
                                            <a href="{{ route('roles.edit', $rol->id) }}" class="btn btn-sm btn-success rounded-pill me-1"><i class="bi bi-pencil-square"></i> Editar</a>
                                            @endcan
                                            
                                            @can('eliminar_roles')
                                            <form action="{{ route('roles.destroy', $rol->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger rounded-pill" onclick="return confirm('¿Confirmas que deseas eliminar este rol?')">
                                                    <i class="bi bi-trash"></i> Eliminar
                                                </button>
                                            </form>
                                            @endcan
                                        @endif
                                    </td>
                                    @endcanany

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($roles->lastPage() > 1)
                @endif
            @else
                <div class="text-center">
                    <p class="text-white mb-3">No hay roles registrados</p>
                    @can('crear_rol')
                    <a href="{{ route('roles.create') }}" class="btn btn-success rounded-pill px-3"><i class="bi bi-plus-circle me-1"></i> Agregar Nuevo</a>
                    @endcan
                </div>
            @endif
        </div>
    </div>
</div>
@endsection