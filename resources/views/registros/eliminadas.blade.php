@extends('layouts.app')

@section('title', 'IPs Eliminadas')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card p-4 shadow-lg">

            <div class="text-center mb-4">
                <h3 class="mb-2 text-danger">
                    <i class="bi bi-trash3"></i> Listado de IPs Eliminadas ({{ $registros->total() }})
                </h3>
                @if($registros->count() > 0 && $registros->lastPage() > 1)
                    <p class="small fw-semibold text-white mb-0">
                        Mostrando {{ $registros->firstItem() }} al {{ $registros->lastItem() }} de {{ $registros->total() }} resultados
                    </p>
                @endif
            </div>

            @if(!empty($buscar))
                <p class="{{ $registros->total() > 0 ? 'text-success' : 'text-danger' }} fw-semibold text-center">
                    {{ $registros->total() > 0 ? 'Se encontraron ' . $registros->total() . ' resultado(s)' : 'No se encontraron resultados' }} para "{{ $buscar }}"
                </p>
            @endif

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-pill text-center alert-auto-hide" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close btn-close-black" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($registros->total() > 0)
                <div class="d-flex justify-content-center align-items-center flex-wrap gap-3 mb-4">
                    <form method="GET" action="{{ route('registros.eliminadas') }}" class="d-flex align-items-center gap-2">
                        <select name="ordenar" class="form-select rounded-pill" style="max-width: 220px;" onchange="this.form.submit()">
                            <option value="nuevo" {{ $ordenar == 'nuevo' ? 'selected' : '' }}>Más recientes</option>
                            <option value="antiguo" {{ $ordenar == 'antiguo' ? 'selected' : '' }}>Más antiguos</option>
                            <option value="asc" {{ $ordenar == 'asc' ? 'selected' : '' }}>IP Ascendente</option>
                            <option value="desc" {{ $ordenar == 'desc' ? 'selected' : '' }}>IP Descendente</option>
                        </select>
                        <input type="text" name="buscar" class="form-control rounded-pill px-3" placeholder="Buscar..." value="{{ $buscar }}" style="max-width: 250px;">
                        <button type="submit" class="btn btn-success rounded-pill"><i class="bi bi-search"></i></button>
                    </form>
                </div>

                <div class="table-responsive" style="overflow-x: auto; white-space: nowrap;">
                    <table class="table table-hover align-middle text-center mb-0 rounded-4" style="min-width: 1100px;">
                        <thead class="table-danger text-dark">
                            <tr>
                                <th>#</th>
                                <th>IP</th>
                                <th>MAC</th>
                                <th>N° Serie</th>
                                <th>Descripción</th>
                                <th>Responsable</th>
                                <th>Dependencia</th>
                                <th>Segmento</th>
                                <th>Tipo Dispositivo</th>
                                <th>Eliminado el</th>
                                
                                @canany(['restaurar_registros_eliminados', 'eliminar_registros_eliminados'])
                                <th>Acciones</th>
                                @endcanany
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($registros as $registro)
                                <tr>
                                    <td>{{ $registros->firstItem() + $loop->index }}</td>
                                    <td>{{ $registro->ip }}</td>
                                    <td>{{ $registro->mac ?? 'Pendiente' }}</td>
                                    <td>{{ $registro->numero_serie ?? 'Pendiente' }}</td>
                                    <td class="text-start">{{ $registro->descripcion ?? '—' }}</td>
                                    <td>{{ $registro->responsable ?? '—' }}</td>
                                    <td>{{ $registro->dependencia->nombre ?? '—' }}</td>
                                    <td>{{ $registro->segmento->segmento ?? 'N/A' }}</td>
                                    <td>{{ $registro->tipo_dispositivo->tipo_dispositivo ?? '—' }}</td>
                                    <td>{{ optional($registro->deleted_at)->timezone('America/Mexico_City')->format('d/m/Y H:i') }}</td>
                                    
                                    @canany(['restaurar_registros_eliminados', 'eliminar_registros_eliminados'])
                                    <td class="text-center">
                                        @can('restaurar_registros_eliminados')
                                        <button class="btn btn-success btn-sm rounded-pill me-1 restore-btn"
                                            data-id="{{ $registro->id }}"
                                            data-ip="{{ $registro->ip }}"
                                            data-mac="{{ $registro->mac ?? 'Pendiente' }}"
                                            data-desc="{{ $registro->descripcion ?? '—' }}"
                                            data-resp="{{ $registro->responsable ?? 'N/A' }}"
                                            data-dep="{{ $registro->dependencia->nombre ?? 'N/A' }}"
                                            data-tipo="{{ $registro->tipo_dispositivo->tipo_dispositivo ?? 'N/A' }}">
                                            <i class="bi bi-arrow-clockwise"></i> Restaurar
                                        </button>
                                        @endcan
                                        
                                        @can('eliminar_registros_eliminados')
                                        <button class="btn btn-danger btn-sm rounded-pill delete-btn"
                                            data-id="{{ $registro->id }}"
                                            data-ip="{{ $registro->ip }}"
                                            data-mac="{{ $registro->mac ?? 'Pendiente' }}"
                                            data-desc="{{ $registro->descripcion ?? '—' }}"
                                            data-resp="{{ $registro->responsable ?? 'N/A' }}"
                                            data-dep="{{ $registro->dependencia->nombre ?? 'N/A' }}"
                                            data-tipo="{{ $registro->tipo_dispositivo->tipo_dispositivo ?? 'N/A' }}">
                                            <i class="bi bi-trash3"></i> Eliminar
                                        </button>
                                        @endcan
                                    </td>
                                    @endcanany
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($registros->lastPage() > 1)
                    <div class="pagination-container mt-4">
                        {{ $registros->onEachSide(1)->links('vendor.pagination.bootstrap-5') }}
                        <form action="{{ route('registros.eliminadas') }}" method="GET" class="d-flex gap-2 align-items-center">
                            <input type="hidden" name="buscar" value="{{ $buscar }}">
                            <input type="hidden" name="ordenar" value="{{ $ordenar }}">
                            <input type="number" name="page" min="1" max="{{ $registros->lastPage() }}" class="form-control text-center rounded-pill" style="width: 90px;" placeholder="Página">
                            <button type="submit" class="btn btn-success btn-sm rounded-pill">Ir</button>
                        </form>
                    </div>
                @endif
            @else
                <div class="text-center">
                    @if(!empty($buscar))
                        <a href="{{ route('registros.eliminadas') }}" class="btn btn-success rounded-pill px-3">
                            <i class="bi bi-arrow-left-circle me-1"></i> Ver listado completo
                        </a>
                    @else
                        <p class="text-white mb-3">No hay registros eliminados</p>
                        <a href="{{ route('registros.ocupadas') }}" class="btn btn-success rounded-pill px-3">
                            <i class="bi bi-arrow-left-circle me-1"></i> Volver a Ocupadas
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

@can('restaurar_registros_eliminados')
<div class="modal fade" id="modalRestaurar" tabindex="-1" aria-labelledby="modalRestaurarLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg rounded-4">
            <div class="modal-header bg-success text-white rounded-top-4">
                <h5 class="modal-title"><i class="bi bi-arrow-clockwise me-2"></i> Confirmar Restauración</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="fw-bold text">¿Deseas restaurar el siguiente registro?</p>
                <ul class="list-group mb-3">
                    <li class="list-group-item"><strong>IP:</strong> <span id="resIp"></span></li>
                    <li class="list-group-item"><strong>MAC:</strong> <span id="resMac"></span></li>
                    <li class="list-group-item"><strong>Descripción:</strong> <span id="resDesc"></span></li>
                    <li class="list-group-item"><strong>Tipo de Dispositivo:</strong> <span id="resTipo"></span></li>
                    <li class="list-group-item"><strong>Responsable:</strong> <span id="resResp"></span></li>
                    <li class="list-group-item"><strong>Dependencia:</strong> <span id="resDep"></span></li>
                </ul>
            </div>
            <div class="modal-footer">
                <form id="formRestaurar" method="POST">
                    @csrf
                    <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success rounded-pill"><i class="bi bi-check-circle"></i> Restaurar</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endcan

@can('eliminar_registros_eliminados')
<div class="modal fade" id="modalEliminarDef" tabindex="-1" aria-labelledby="modalEliminarDefLabel" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg rounded-4">
            <div class="modal-header bg-danger text-white rounded-top-4">
                <h5 class="modal-title"><i class="bi bi-exclamation-triangle-fill me-2"></i> Eliminar Permanentemente</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="fw-bold text">Esta acción no se puede deshacer. Confirma la eliminación definitiva del siguiente registro:</p>
                <ul class="list-group mb-3">
                    <li class="list-group-item"><strong>IP:</strong> <span id="delIp"></span></li>
                    <li class="list-group-item"><strong>MAC:</strong> <span id="delMac"></span></li>
                    <li class="list-group-item"><strong>Descripción:</strong> <span id="delDesc"></span></li>
                    <li class="list-group-item"><strong>Tipo de Dispositivo:</strong> <span id="delTipo"></span></li>
                    <li class="list-group-item"><strong>Responsable:</strong> <span id="delResp"></span></li>
                    <li class="list-group-item"><strong>Dependencia:</strong> <span id="delDep"></span></li>
                </ul>
            </div>
            <div class="modal-footer">
                <form id="formEliminarDef" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger rounded-pill"><i class="bi bi-trash3"></i> Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endcan
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const modalRestaurar = new bootstrap.Modal(document.getElementById('modalRestaurar'));
    const modalEliminarDef = new bootstrap.Modal(document.getElementById('modalEliminarDef'));

    document.querySelectorAll('.restore-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;
            document.getElementById('resIp').textContent = btn.dataset.ip;
            document.getElementById('resMac').textContent = btn.dataset.mac;
            document.getElementById('resDesc').textContent = btn.dataset.desc;
            document.getElementById('resTipo').textContent = btn.dataset.tipo;
            document.getElementById('resResp').textContent = btn.dataset.resp;
            document.getElementById('resDep').textContent = btn.dataset.dep;
            document.getElementById('formRestaurar').action = `/registros/${id}/restore`;
            modalRestaurar.show();
        });
    });

    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;
            document.getElementById('delIp').textContent = btn.dataset.ip;
            document.getElementById('delMac').textContent = btn.dataset.mac;
            document.getElementById('delDesc').textContent = btn.dataset.desc;
            document.getElementById('delTipo').textContent = btn.dataset.tipo;
            document.getElementById('delResp').textContent = btn.dataset.resp;
            document.getElementById('delDep').textContent = btn.dataset.dep;
            document.getElementById('formEliminarDef').action = `/registros/${id}/forzar`;
            modalEliminarDef.show();
        });
    });
});
</script>
@endpush