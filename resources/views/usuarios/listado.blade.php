@extends('layouts.app')

@section('title', 'Listado de Usuarios')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card p-4 shadow-lg">

            <div class="text-center mb-4">
                <h3 class="mb-2">
                    <i class="bi bi-people"></i> Listado de Usuarios ({{ $usuarios->total() }})
                </h3>
                @if($usuarios->count() > 0 && $usuarios->lastPage() > 1)
                    <p class="small fw-semibold text-white mb-0">
                        Mostrando {{ $usuarios->firstItem() }} al {{ $usuarios->lastItem() }} de {{ $usuarios->total() }} resultados
                    </p>
                @endif
            </div>

            @if(!empty($buscar))
                <p class="{{ $usuarios->count() > 0 ? 'text-success' : 'text-danger' }} fw-semibold text-center">
                    {{ $usuarios->count() > 0 ? 'Se encontraron ' . $usuarios->count() . ' resultado(s)' : 'No se encontraron resultados' }} para "{{ $buscar }}"
                </p>
            @endif

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-pill text-center alert-auto-hide" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close btn-close-black" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($usuarios->count() > 0)
                <div class="d-flex justify-content-center align-items-center flex-wrap gap-3 mb-4">
                    @can('crear_usuarios')
                    <a href="{{ route('usuarios.create') }}" class="btn btn-success rounded-pill px-3">
                        <i class="bi bi-plus-circle me-1"></i> Agregar Nuevo
                    </a>
                    @endcan

                    <form method="GET" action="{{ route('usuarios.index') }}" class="d-flex align-items-center gap-2">
                        <select name="ordenar" class="form-select rounded-pill" style="max-width: 220px;" onchange="this.form.submit()">
                            <option value="asc" {{ $ordenar == 'asc' ? 'selected' : '' }}>A-Z</option>
                            <option value="desc" {{ $ordenar == 'desc' ? 'selected' : '' }}>Z-A</option>
                            <option value="nuevo" {{ $ordenar == 'nuevo' ? 'selected' : '' }}>Más recientes</option>
                            <option value="antiguo" {{ $ordenar == 'antiguo' ? 'selected' : '' }}>Más antiguos</option>
                        </select>

                        <input type="text" name="buscar" class="form-control rounded-pill px-3" placeholder="Buscar..." value="{{ request('buscar') }}" style="max-width: 250px;">
                        <button type="submit" class="btn btn-success rounded-pill"><i class="bi bi-search"></i></button>
                    </form>
                </div>

                <div class="table-responsive" style="overflow-x: auto; white-space: nowrap;">
                    <table class="table table-hover align-middle text-center mb-0 rounded-4" style="min-width: 700px;">
                        <thead class="table-primary text-dark">
                            <tr>
                                <th style="width: 5%;">#</th>
                                @canany(['editar_usuarios', 'eliminar_usuarios'])
                                    <th style="width: 25%;">Nombre</th>
                                    <th style="width: 15%;">Código</th>
                                    <th style="width: 20%;">Dependencia</th>
                                    <th style="width: 20%;">Roles</th>
                                    <th style="width: 15%;">Acciones</th>
                                @else
                                    <th style="width: 40%;">Nombre</th> 
                                    <th style="width: 15%;">Código</th>
                                    <th style="width: 20%;">Dependencia</th>
                                    <th style="width: 20%;">Roles</th>
                                @endcanany
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($usuarios as $usuario)
                                <tr>
                                    <td>{{ $usuarios->firstItem() + $loop->index }}</td>
                                    <td class="text-start">{{ $usuario->nombre }}</td>
                                    <td>{{ $usuario->codigo }}</td>
                                    <td>{{ $usuario->dependencia->nombre ?? '—' }}</td>
                                    
                                    <td>
                                        @forelse($usuario->roles as $rol)
                                            <span class="badge bg-primary rounded-pill">{{ $rol->nombre_rol }}</span>
                                        @empty
                                            <span class="badge bg-secondary rounded-pill">Sin roles</span>
                                        @endforelse
                                    </td>
                                    
                                    @canany(['editar_usuarios', 'eliminar_usuarios'])
                                    <td>
                                        @can('editar_usuarios')
                                        <a href="{{ route('usuarios.edit', $usuario->id) }}" class="btn btn-sm btn-success rounded-pill me-1"><i class="bi bi-pencil-square"></i> Editar</a>
                                        @endcan
                                        
                                        @if(!$usuario->roles->contains('nombre_rol', 'Programador'))
                                            @can('eliminar_usuarios')
                                            <form action="{{ route('usuarios.destroy', $usuario->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger rounded-pill" onclick="return confirm('¿Confirmas que deseas eliminar este usuario?')">
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

                @if($usuarios->lastPage() > 1)
                    <div class="pagination-container mt-4">
                        {{ $usuarios->onEachSide(1)->links('vendor.pagination.bootstrap-5') }}
                        <form action="{{ route('usuarios.index') }}" method="GET" class="d-flex gap-2 align-items-center">
                            <input type="number" name="page" min="1" max="{{ $usuarios->lastPage() }}" class="form-control text-center rounded-pill" style="width: 90px;" placeholder="Página">
                            <button type="submit" class="btn btn-success btn-sm rounded-pill">Ir</button>
                        </form>
                    </div>
                @endif
            @else
                <div class="text-center">
                    <p class="text-white mb-3">No hay usuarios registrados</p>
                    
                    @can('crear_usuarios')
                    <a href="{{ route('usuarios.create') }}" class="btn btn-success rounded-pill px-3"><i class="bi bi-plus-circle me-1"></i> Agregar Nuevo</a>
                    @endcan
                </div>
            @endif
        </div>
    </div>
</div>
@endsection