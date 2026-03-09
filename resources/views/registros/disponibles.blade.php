@extends('layouts.app')

@section('title', 'IPs Disponibles')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card p-4 shadow-lg">

            <div class="text-center mb-4">
                <h3 class="mb-2">
                    <i class="bi bi-wifi"></i> Listado de IPs Disponibles
                    @if($disponibles ?? null)
                    ({{ count($disponibles) }})
                    @endif
                </h3>
                Red: {{ $red->direccion_completa }} |
                Segmento: {{ $segmento->segmento ?? 'N/A' }}
                @if($dispositivosDeRed->isNotEmpty())
                    <br>
                    <span class="small fw-semibold">Dispositivos: {{ $dispositivosDeRed->pluck('tipo_dispositivo')->implode(', ') }}</span>
                @endif
            </div>

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show alert-auto-hide" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form method="GET" action="{{ route('registros.disponibles') }}" class="d-flex justify-content-center align-items-center flex-wrap gap-2 mb-4">
                <select name="red_id" class="form-select rounded-pill" style="max-width: 220px;" onchange="this.form.submit()">
                    <option value="">Red</option>
                    @foreach($redes as $r)
                        <option value="{{ $r->id }}" {{ ($redSeleccionada ?? null) == $r->id ? 'selected' : '' }}>
                            {{ $r->direccion_completa }}
                        </option>
                    @endforeach
                </select>

                @if($red->usa_segmentos)
                    <select name="segmento_id" class="form-select rounded-pill" style="max-width: 200px;" onchange="this.form.submit()">
                        <option value="">Segmento</option>
                        @foreach($segmentos as $seg)
                            <option value="{{ $seg->id }}" {{ ($segmentoSeleccionado ?? null) == $seg->id ? 'selected' : '' }}>
                                {{ $seg->segmento }}
                            </option>
                        @endforeach
                    </select>
                @endif
            </form>

            @if($errorFaltanSegmentos)
                <div class="text-center mt-3">
                    <p class="text-warning fw-semibold mb-3">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        La red {{ $red->direccion_completa }} usa segmentos, pero no tiene ninguno registrado.
                        <br>
                        No se pueden calcular las IPs disponibles.
                    </p>
                </div>

            @elseif(!($redSeleccionada ?? null))
                <div class="text-center mt-3">
                    <p class="text-white mb-3">Seleccione una red válida para ver las IPs disponibles.</p>
                </div>

            @elseif(count($disponibles) > 0)
                <div class="table-responsive" style="overflow-x: auto; white-space: nowrap;">
                    <table class="table table-hover align-middle text-center mb-0 rounded-4" style="min-width: 700px;">
                        <thead class="table-primary text-dark">
                            <tr>
                                <th>#</th>
                                <th>Dirección IP Disponible</th>
                                @can('crear_registros')
                                <th>Acciones</th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($disponibles as $index => $ip)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $ip }}</td>
                                    
                                    @can('crear_registros')
                                    <td>
                                        <a href="{{ route('registros.usar', [
                                            'ip' => $ip,
                                            'segmento_id' => $segmento->id ?? null,
                                            'red_id' => $red->id
                                        ]) }}" class="btn btn-success btn-sm rounded-pill">
                                            <i class="bi bi-plus-circle"></i> Usar
                                        </a>
                                    </td>
                                    @endcan
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            @else
                <div class="text-center mt-3">
                    <p class="text-white mb-3">No hay direcciones IP disponibles en este rango.</p>
                </div>
            @endif

        </div>
    </div>
</div>
@endsection