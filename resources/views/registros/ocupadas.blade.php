@extends('layouts.app')

@section('title', 'IPs Ocupadas')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card p-3 shadow-lg">

                <div class="text-center mb-4">
                    <h3 class="mb-2">
                        <i class="bi bi-router"></i> Listado de IPs Ocupadas ({{ $registros->total() }})
                    </h3>
                    @if ($registros->count() > 0 && $registros->lastPage() > 1)
                        <p class="small fw-semibold text-white mb-0">
                            Mostrando {{ $registros->firstItem() }} al {{ $registros->lastItem() }} de
                            {{ $registros->total() }} resultados
                        </p>
                    @endif
                </div>

                @if (!empty($buscar))
                    <p class="{{ $registros->total() > 0 ? 'text-success' : 'text-danger' }} fw-semibold text-center">
                        {{ $registros->total() > 0 ? 'Se encontraron ' . $registros->total() . ' resultado(s)' : 'No se encontraron resultados' }}
                        para "{{ $buscar }}"
                    </p>
                @endif

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show rounded-pill text-center alert-auto-hide"
                        role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close btn-close-black" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if ($registros->count() > 0)
                    <div class="d-flex justify-content-center align-items-center flex-wrap gap-3 mb-4">
                        <form method="GET" action="{{ route('registros.ocupadas') }}"
                            class="d-flex align-items-center gap-2 flex-wrap" id="filtrosForm">

                            @if (!empty($buscar))
                                <input type="hidden" name="buscar" value="{{ $buscar }}">
                            @endif
                            <select name="ordenar" class="form-select rounded-pill" style="width: 180px;"
                                onchange="this.form.submit()">
                                <option value="nuevo" {{ ($ordenar ?? '') == 'nuevo' ? 'selected' : '' }}>Más recientes
                                </option>
                                <option value="antiguo" {{ ($ordenar ?? '') == 'antiguo' ? 'selected' : '' }}>Más antiguos
                                </option>
                                <option value="asc" {{ ($ordenar ?? '') == 'asc' ? 'selected' : '' }}>IP Ascendente
                                </option>
                                <option value="desc" {{ ($ordenar ?? '') == 'desc' ? 'selected' : '' }}>IP Descendente
                                </option>
                            </select>
                            <select name="red_id" id="filtro_red" class="form-select rounded-pill" style="width: 220px;">
                                <option value="">Todas las Redes</option>
                                @foreach ($redes as $r)
                                    <option value="{{ $r->id }}" data-usa-seg="{{ $r->usa_segmentos ? '1' : '0' }}"
                                        {{ (string) ($redSeleccionada ?? '') === (string) $r->id ? 'selected' : '' }}>
                                        {{ $r->direccion_completa }}
                                    </option>
                                @endforeach
                            </select>
                            <select name="segmento_id" id="filtro_segmento" class="form-select rounded-pill"
                                style="width: 180px;">
                                <option value="">{{ $red && $red->usa_segmentos ? 'Todos los Segmentos' : 'N/A' }}
                                </option>
                                @foreach ($segmentos as $seg)
                                    <option value="{{ $seg->id }}"
                                        {{ (string) ($segmentoSeleccionado ?? '') === (string) $seg->id ? 'selected' : '' }}>
                                        {{ $seg->segmento }}
                                    </option>
                                @endforeach
                            </select>
                            <select name="tipo_dispositivo_id" class="form-select rounded-pill" style="width: 220px;"
                                onchange="this.form.submit()">
                                <option value="">Todos los Tipos</option>
                                @foreach ($dispositivos as $disp)
                                    <option value="{{ $disp->id }}"
                                        {{ (string) ($dispositivoSeleccionado ?? '') === (string) $disp->id ? 'selected' : '' }}>
                                        {{ $disp->tipo_dispositivo }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>

                    <div class="table-responsive" style="overflow-x: auto; white-space: nowrap;">
                        <table class="table table-hover align-middle text-center mb-0 rounded-4" style="min-width: 1100px;">
                            <thead class="table-primary text-dark">
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

                                    @canany(['editar_registros', 'eliminar_registros'])
                                        <th>Acciones</th>
                                    @endcanany

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($registros as $registro)
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

                                        @canany(['editar_registros', 'eliminar_registros'])
                                            <td>
                                                @can('editar_registros')
                                                    <a href="{{ route('registros.modificar', ['ip' => $registro->ip]) }}"
                                                        class="btn btn-sm btn-success rounded-pill me-1" title="Editar">
                                                        <i class="bi bi-pencil-square"></i> Editar
                                                    </a>
                                                @endcan

                                                @can('eliminar_registros')
                                                    <a href="{{ route('registros.eliminar', ['ip' => $registro->ip]) }}"
                                                        class="btn btn-sm btn-danger rounded-pill" title="Eliminar">
                                                        <i class="bi bi-trash3"></i> Eliminar
                                                    </a>
                                                @endcan
                                            </td>
                                        @endcanany

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if ($registros->lastPage() > 1)
                        <div class="pagination-container mt-4">
                            {{ $registros->onEachSide(1)->links('vendor.pagination.bootstrap-5') }}
                            <form action="{{ route('registros.ocupadas') }}" method="GET"
                                class="d-flex gap-2 align-items-center">
                                <input type="number" name="page" min="1" max="{{ $registros->lastPage() }}"
                                    class="form-control text-center rounded-pill" style="width: 90px;" placeholder="Página">
                                <button type="submit" class="btn btn-success btn-sm rounded-pill">Ir</button>
                            </form>
                        </div>
                    @endif
                @else
                    <div class="text-center">
                        @if (!empty($buscar))
                            <p class="text-white mb-3">No se encontraron registros para la búsqueda: "{{ $buscar }}"
                            </p>
                            <a href="{{ route('registros.ocupadas') }}" class="btn btn-success rounded-pill px-3">
                                <i class="bi bi-arrow-left-circle me-1"></i> Ver listado completo
                            </a>
                        @else
                            <p class="text-white mb-3">No hay registros de IPs ocupadas</p>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const redSelect = document.getElementById('filtro_red');
            const segmentoSelect = document.getElementById('filtro_segmento');
            const form = document.getElementById('filtrosForm');

            function handleRedChange() {
                const selected = redSelect.selectedOptions[0];
                const usaSegmentos = selected?.dataset?.usaSeg === '1';

                if (!usaSegmentos) {
                    segmentoSelect.innerHTML = `<option value="">N/A</option>`;
                    segmentoSelect.disabled = true;
                    form.submit();
                } else {
                    segmentoSelect.disabled = false;
                    form.submit();
                }
            }

            redSelect.addEventListener('change', handleRedChange);
            segmentoSelect.addEventListener('change', () => form.submit());
        });
    </script>
@endpush
