@extends('layouts.app')

@section('title', 'Listado de Dependencias')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card p-4 shadow-lg">

            <div class="text-center mb-4">
                <h3 class="mb-2">
                    <i class="bi bi-building"></i> Listado de Dependencias ({{ $dependencias->total() }})
                </h3>
                @if($dependencias->count() > 0 && $dependencias->lastPage() > 1)
                    <p class="small fw-semibold text-white mb-0">
                        Mostrando {{ $dependencias->firstItem() }} al {{ $dependencias->lastItem() }} de {{ $dependencias->total() }} resultados
                    </p>
                @endif
            </div>

            @if(!empty($buscar))
                <p class="{{ $dependencias->count() > 0 ? 'text-success' : 'text-danger' }} fw-semibold text-center">
                    {{ $dependencias->count() > 0 ? 'Se encontraron ' . $dependencias->count() . ' resultado(s)' : 'No se encontraron resultados' }} para "{{ $buscar }}"
                </p>
            @endif

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-pill text-center alert-auto-hide" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close btn-close-black" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($dependencias->count() > 0)
                <div class="d-flex justify-content-center align-items-center flex-wrap gap-3 mb-4">
                    
                    @can('crear_dependencias')
                    <a href="{{ route('dependencias.create') }}" class="btn btn-success rounded-pill px-3">
                        <i class="bi bi-plus-circle me-1"></i> Agregar Nueva
                    </a>
                    @endcan

                    <form method="GET" action="{{ route('dependencias.index') }}" class="d-flex align-items-center gap-2">
                        <select name="ordenar" class="form-select rounded-pill" style="max-width: 220px;" onchange="this.form.submit()">
                            <option value="nuevo" {{ $ordenar == 'nuevo' ? 'selected' : '' }}>Más recientes</option>
                            <option value="antiguo" {{ $ordenar == 'antiguo' ? 'selected' : '' }}>Más antiguos</option>
                            <option value="asc" {{ $ordenar == 'asc' ? 'selected' : '' }}>A-Z</option>
                            <option value="desc" {{ $ordenar == 'desc' ? 'selected' : '' }}>Z-A</option>
                        </select>

                        <input type="text" name="buscar" class="form-control rounded-pill px-3" placeholder="Buscar..." value="{{ request('buscar') }}" style="max-width: 250px;">
                        <button type="submit" class="btn btn-success rounded-pill"><i class="bi bi-search"></i></button>
                    </form>
                </div>

                <div class="table-responsive" style="overflow-x: auto; white-space: nowrap;">
                    <table class="table table-hover align-middle text-center mb-0 rounded-4" style="min-width: 600px;">
                        <thead class="table-primary text-dark">
                            <tr>
                                <th style="width: 10%;">#</th>
                                
                                @canany(['editar_dependencias', 'eliminar_dependencias'])
                                    <th style="width: 55%;">Nombre</th>
                                    <th style="width: 35%;">Acciones</th>
                                @else
                                    <th style="width: 90%;">Nombre</th>
                                @endcanany
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dependencias as $dependencia)
                                <tr>
                                    <td>{{ $dependencias->firstItem() + $loop->index }}</td>
                                    <td class="text-start">{{ $dependencia->nombre }}</td>
                                    
                                    @canany(['editar_dependencias', 'eliminar_dependencias'])
                                    <td>
                                        
                                        @can('editar_dependencias')
                                        <a href="{{ route('dependencias.edit', $dependencia->id) }}" class="btn btn-sm btn-success rounded-pill me-1" title="Editar"><i class="bi bi-pencil-square"></i> Editar</a>
                                        @endcan
                                        
                                        @can('eliminar_dependencias')
                                        <form action="{{ route('dependencias.destroy', $dependencia->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger rounded-pill" onclick="return confirm('¿Confirmas que deseas eliminar esta dependencia?')"><i class="bi bi-trash"></i> Eliminar</button>
                                        </form>
                                        @endcan
                                    
                                    </td>
                                    @endcanany
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($dependencias->lastPage() > 1)
                    <div class="pagination-container mt-4">
                        {{ $dependencias->onEachSide(1)->links('vendor.pagination.bootstrap-5') }}
                        <form action="{{ route('dependencias.index') }}" method="GET" class="d-flex gap-2 align-items-center">
                            <input type="number" name="page" min="1" max="{{ $dependencias->lastPage() }}" class="form-control text-center rounded-pill" style="width: 90px;" placeholder="Página">
                            <button type="submit" class="btn btn-success btn-sm rounded-pill">Ir</button>
                        </form>
                    </div>
                @endif
            @else
                <div class="text-center">
                    <p class="text-white mb-3">No hay dependencias registradas</p>
                    
                    @can('crear_dependencias')
                    <a href="{{ route('dependencias.create') }}" class="btn btn-success rounded-pill px-3"><i class="bi bi-plus-circle me-1"></i> Agregar Nueva</a>
                    @endcan
                </div>
            @endif
        </div>
    </div>
</div>
@endsection