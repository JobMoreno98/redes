@extends('layouts.app')

@section('title', 'Listado de Redes')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card p-4 shadow-lg">

            <div class="text-center mb-4">
                <h3 class="mb-2">
                    <i class="bi bi-diagram-3"></i> Listado de Redes ({{ $redes->total() }})
                </h3>
                @if($redes->count() > 0 && $redes->lastPage() > 1)
                    <p class="small fw-semibold text-white mb-0">
                        Mostrando {{ $redes->firstItem() }} al {{ $redes->lastItem() }} de {{ $redes->total() }} resultados
                    </p>
                @endif
            </div>

            @if(!empty($buscar))
                <p class="{{ $redes->count() > 0 ? 'text-success' : 'text-danger' }} fw-semibold text-center">
                    {{ $redes->count() > 0 ? 'Se encontraron ' . $redes->count() . ' resultado(s)' : 'No se encontraron resultados' }} para "{{ $buscar }}"
                </p>
            @endif

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-pill text-center alert-auto-hide" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close btn-close-black" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @can('crear_redes')
            <div class="mb-3 text-center">
                <a href="{{ route('redes.create') }}" class="btn btn-success rounded-pill px-4">
                    <i class="bi bi-plus-circle me-1"></i> Agregar Nueva
                </a>
            </div>
            @endcan
            
            <form method="GET" action="{{ route('redes.index') }}" class="d-flex justify-content-center align-items-center gap-2 flex-wrap mb-4">
                <select name="ordenar" class="form-select rounded-pill" style="width: 180px;" onchange="this.form.submit()">
                    <option value="nuevo" {{ $ordenar == 'nuevo' ? 'selected' : '' }}>Más recientes</option>
                    <option value="antiguo" {{ $ordenar == 'antiguo' ? 'selected' : '' }}>Más antiguos</option>
                    <option value="asc" {{ $ordenar == 'asc' ? 'selected' : '' }}>Dirección base A-Z</option>
                    <option value="desc" {{ $ordenar == 'desc' ? 'selected' : '' }}>Dirección base Z-A</option>
                </select>

                <input type="text" name="buscar" class="form-control rounded-pill px-3" placeholder="Buscar dirección o descripción..." value="{{ $buscar }}" style="width: 250px;">
                <button type="submit" class="btn btn-success rounded-pill"><i class="bi bi-search"></i></button>
            </form>

            @if($redes->count() > 0)
                <div class="table-responsive" style="overflow-x: auto; white-space: nowrap;">
                    <table class="table table-hover align-middle text-center mb-0 rounded-4" style="min-width: 650px;">
                        <thead class="table-primary text-dark">
                            <tr>
                                <th>#</th>
                                <th>Dirección Base</th>
                                <th>Descripción</th>
                                <th>Usa Segmentos</th>
                                <th>Hosts Reservados</th>
                                @canany(['editar_redes', 'eliminar_redes'])
                                <th>Acciones</th>
                                @endcanany
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($redes as $red)
                                <tr>
                                    <td>{{ $redes->firstItem() + $loop->index }}</td>
                                    <td>{{ $red->direccion_completa }}</td>
                                    <td class="text-start">{{ $red->descripcion ?? '—' }}</td>
                                    <td>{{ $red->usa_segmentos ? 'Sí' : 'No' }}</td>
                                    <td class="text-start">
                                        {{ $red->hosts_reservados_string ?: '—' }}
                                    </td>
                                    {{-- PROTEGER COLUMNA ACCIONES --}}
                                    @canany(['editar_redes', 'eliminar_redes'])
                                    <td>
                                        @can('editar_redes')
                                        <a href="{{ route('redes.edit', $red->id) }}" class="btn btn-sm btn-success rounded-pill me-1">
                                            <i class="bi bi-pencil-square"></i> Editar
                                        </a>
                                        @endcan
                                        
                                        @can('eliminar_redes')
                                        <form action="{{ route('redes.destroy', $red->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger rounded-pill" onclick="return confirm('¿Confirmas que deseas eliminar esta red?')">
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

                @if($redes->lastPage() > 1)
                    <div class="pagination-container mt-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
                        {{ $redes->onEachSide(1)->links('vendor.pagination.bootstrap-5') }}
                        <form action="{{ route('redes.index') }}" method="GET" class="d-flex gap-2 align-items-center">
                            <input type="number" name="page" min="1" max="{{ $redes->lastPage() }}" class="form-control text-center rounded-pill" style="width: 90px;" placeholder="Página">
                            <button type="submit" class="btn btn-success btn-sm rounded-pill">Ir</button>
                        </form>
                    </div>
                @endif
            @else
                <div class="text-center">
                    <p class="text-white mb-3">No se encontraron redes</p>
                    @can('crear_redes')
                    <a href="{{ route('redes.create') }}" class="btn btn-success rounded-pill px-3">
                        <i class="bi bi-plus-circle me-1"></i> Agregar Nueva Red
                    </a>
                    @endcan
                </div>
            @endif

        </div>
    </div>
</div>
@endsection