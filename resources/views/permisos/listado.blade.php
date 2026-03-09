@extends('layouts.app')

@section('title', 'Listado de Permisos')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card p-4 shadow-lg">

            <div class="text-center mb-4">
                <h3 class="mb-2">
                    <i class="bi bi-shield-lock"></i> Listado de Permisos ({{ $permisos->total() }})
                </h3>
                @if($permisos->count() > 0 && $permisos->lastPage() > 1)
                    <p class="small fw-semibold text-white mb-0">
                        Mostrando {{ $permisos->firstItem() }} al {{ $permisos->lastItem() }} de {{ $permisos->total() }} resultados
                    </p>
                @endif
            </div>

            @if(!empty($buscar))
                <p class="{{ $permisos->count() > 0 ? 'text-success' : 'text-danger' }} fw-semibold text-center">
                    {{ $permisos->count() > 0 ? 'Se encontraron ' . $permisos->count() . ' resultado(s)' : 'No se encontraron resultados' }} para "{{ $buscar }}"
                </p>
            @endif

            <form method="GET" action="{{ route('permisos.index') }}" class="d-flex justify-content-center align-items-center gap-2 flex-wrap mb-4">
                <select name="ordenar" class="form-select rounded-pill" style="width: 180px;" onchange="this.form.submit()">
                    <option value="nuevo" {{ $ordenar == 'nuevo' ? 'selected' : '' }}>Más recientes</option>
                    <option value="antiguo" {{ $ordenar == 'antiguo' ? 'selected' : '' }}>Más antiguos</option>
                    <option value="asc" {{ $ordenar == 'asc' ? 'selected' : '' }}>Nombre A-Z</option>
                    <option value="desc" {{ $ordenar == 'desc' ? 'selected' : '' }}>Nombre Z-A</option>
                </select>

                <input type="text" name="buscar" class="form-control rounded-pill px-3" placeholder="Buscar permiso..." value="{{ $buscar }}" style="width: 250px;">
                <button type="submit" class="btn btn-success rounded-pill"><i class="bi bi-search"></i></button>
            </form>

            @if($permisos->count() > 0)
                <div class="table-responsive" style="overflow-x: auto; white-space: nowrap;">
                    <table class="table table-hover align-middle text-center mb-0 rounded-4" style="min-width: 600px;">
                        <thead class="table-primary text-dark">
                            <tr>
                                <th style="width: 10%;">#</th>
                                <th style="width: 50%;">Nombre del Permiso</th>
                                <th style="width: 40%;">Registrado el</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($permisos as $permiso)
                                <tr>
                                    <td>{{ $permisos->firstItem() + $loop->index }}</td>
                                    <td class="text-start">{{ $permiso->nombre_permiso }}</td>
                                    <td>{{ $permiso->created_at->timezone('America/Mexico_City')->format('d/m/Y H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($permisos->lastPage() > 1)
                    <div class="pagination-container mt-4">
                        {{ $permisos->onEachSide(1)->links('vendor.pagination.bootstrap-5') }}
                        <form action="{{ route('permisos.index') }}" method="GET" class="d-flex gap-2 align-items-center">
                            <input type="number" name="page" min="1" max="{{ $permisos->lastPage() }}" class="form-control text-center rounded-pill" style="width: 90px;" placeholder="Página">
                            <button type="submit" class="btn btn-success btn-sm rounded-pill">Ir</button>
                        </form>
                    </div>
                @endif

            @else
                <div class="text-center">
                    <p class="text-white mb-3">
                        @if(!empty($buscar))
                            No se encontraron permisos que coincidan con "{{ $buscar }}".
                        @else
                            No hay permisos registrados en la base de datos.
                        @endif
                    </p>
                    <p class="small text-white-50">Los permisos se gestionan desde el `PermisoSeeder`.</p>
                </div>
            @endif

        </div>
    </div>
</div>
@endsection