@extends('layouts.app')

@section('title', 'Listado de Segmentos')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card p-4 shadow-lg">

            <div class="text-center mb-4">
                <h3 class="mb-2">
                    <i class="bi bi-diagram-3"></i> Listado de Segmentos ({{ $segmentos->total() }})
                </h3>
                @if($segmentos->count() > 0 && $segmentos->lastPage() > 1)
                    <p class="small fw-semibold text-white mb-0">
                        Mostrando {{ $segmentos->firstItem() }} al {{ $segmentos->lastItem() }} de {{ $segmentos->total() }} resultados
                    </p>
                @endif
            </div>

            @if(!empty($buscar))
                <p class="{{ $segmentos->count() > 0 ? 'text-success' : 'text-danger' }} fw-semibold text-center">
                    {{ $segmentos->count() > 0 ? 'Se encontraron ' . $segmentos->count() . ' resultado(s)' : 'No se encontraron resultados' }} para "{{ $buscar }}"
                </p>
            @endif

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-pill text-center alert-auto-hide" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close btn-close-black" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @can('crear_segmentos')
            <div class="mb-3 text-center">
                <a href="{{ route('segmentos.create') }}" class="btn btn-success rounded-pill px-4">
                    <i class="bi bi-plus-circle me-1"></i> Agregar Nuevo
                </a>
            </div>
            @endcan

        
            <form method="GET" action="{{ route('segmentos.index') }}" class="d-flex justify-content-center align-items-center gap-2 flex-wrap mb-4">
                <select name="red_id" class="form-select rounded-pill" style="width: 180px;" onchange="this.form.submit()">
                    <option value="">Todas las redes</option>
                    @foreach($reds as $red)
                        <option value="{{ $red->id }}" {{ $redSeleccionada == $red->id ? 'selected' : '' }}>
                            {{ $red->direccion_base }}
                        </option>
                    @endforeach
                </select>

                <select name="ordenar" class="form-select rounded-pill" style="width: 180px;" onchange="this.form.submit()">
                    <option value="nuevo" {{ $ordenar == 'nuevo' ? 'selected' : '' }}>Más recientes</option>
                    <option value="antiguo" {{ $ordenar == 'antiguo' ? 'selected' : '' }}>Más antiguos</option>
                    <option value="asc" {{ $ordenar == 'asc' ? 'selected' : '' }}>Segmento ascendente</option>
                    <option value="desc" {{ $ordenar == 'desc' ? 'selected' : '' }}>Segmento descendente</option>
                </select>

                <input type="text" name="buscar" class="form-control rounded-pill px-3" placeholder="Buscar segmento o red..." value="{{ $buscar }}" style="width: 250px;">
                <button type="submit" class="btn btn-success rounded-pill"><i class="bi bi-search"></i></button>
            </form>

            @if($segmentos->count() > 0)
                <div class="table-responsive" style="overflow-x: auto; white-space: nowrap;">
                    <table class="table table-hover align-middle text-center mb-0 rounded-4" style="min-width: 650px;">
                        <thead class="table-primary text-dark">
                            <tr>
                                <th>#</th>
                                <th>Red</th>
                                <th>Segmento</th>
                                @canany(['editar_segmentos', 'eliminar_segmentos'])
                                <th>Acciones</th>
                                @endcanany
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($segmentos as $segmento)
                                <tr>
                                    <td>{{ $segmentos->firstItem() + $loop->index }}</td>
                                    <td>{{ $segmento->red->direccion_base ?? '—' }}</td>
                                    <td class="text-start">{{ $segmento->segmento }}</td>
                                    
                                    @canany(['editar_segmentos', 'eliminar_segmentos'])
                                    <td>
                                        @can('editar_segmentos')
                                        <a href="{{ route('segmentos.edit', $segmento->id) }}" class="btn btn-sm btn-success rounded-pill me-1">
                                            <i class="bi bi-pencil-square"></i> Editar
                                        </a>
                                        @endcan
                                        
                                        @can('eliminar_segmentos')
                                        <form action="{{ route('segmentos.destroy', $segmento->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger rounded-pill" onclick="return confirm('¿Confirmas que deseas eliminar este segmento?')">
                                                <i class="bi bi-trash"></i> Eliminar
                                            </button>
                                        </form>
                                        @endcan
                                    </td>
                                    @endcanany
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($segmentos->lastPage() > 1)
                    <div class="pagination-container mt-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
                        {{ $segmentos->onEachSide(1)->links('vendor.pagination.bootstrap-5') }}
                        <form action="{{ route('segmentos.index') }}" method="GET" class="d-flex gap-2 align-items-center">
                            <input type="number" name="page" min="1" max="{{ $segmentos->lastPage() }}" class="form-control text-center rounded-pill" style="width: 90px;" placeholder="Página">
                            <button type="submit" class="btn btn-success btn-sm rounded-pill">Ir</button>
                        </form>
                    </div>
                @endif
            @else
                <div class="text-center">
                    <p class="text-white mb-3">No se encontraron segmentos</p>
                    @can('crear_segmentos')
                    <a href="{{ route('segmentos.create') }}" class="btn btn-success rounded-pill px-3">
                        <i class="bi bi-plus-circle me-1"></i> Agregar Nuevo
                    </a>
                    @endcan
                </div>
            @endif

        </div>
    </div>
</div>
@endsection