@extends('layouts.app')

@section('title', 'Editar Registro')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card p-4 shadow-lg">
            <h3 class="text-center mb-4">
                <i class="bi bi-pencil-square me-1"></i> Editar Registro
            </h3>

            <form method="POST" action="{{ route('registros.update', $registro->id) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Red</label>
                    <select name="red_id" id="red_id"
                            class="form-select rounded-pill @error('red_id') is-invalid @enderror"
                            required>
                        <option value="">Seleccionar red</option>
                        @foreach ($redes as $r)
                            <option value="{{ $r->id }}"
                                data-usa-segmentos="{{ $r->usa_segmentos ? '1' : '0' }}"
                                data-base="{{ $r->direccion_base }}"
                                {{ old('red_id', $registro->red_id) == $r->id ? 'selected' : '' }}>
                                {{ $r->direccion_completa }}
                            </option>
                        @endforeach
                    </select>
                    @error('red_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3" id="segmento_container">
                    <label class="form-label">Segmento</label>
                    <select name="segmento_id" id="segmento_id"
                            class="form-select rounded-pill @error('segmento_id') is-invalid @enderror">
                        <option value="">Seleccionar segmento</option>
                        @foreach ($segmentos as $seg)
                            <option value="{{ $seg->id }}"
                                data-seg="{{ $seg->segmento }}"
                                {{ old('segmento_id', $registro->segmento_id) == $seg->id ? 'selected' : '' }}>
                                {{ $seg->segmento }}
                            </option>
                        @endforeach
                    </select>
                    @error('segmento_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Tipo de Dispositivo</label>
                    <select name="tipo_dispositivo_id"
                            class="form-select rounded-pill @error('tipo_dispositivo_id') is-invalid @enderror"
                            required>
                        <option value="">Seleccionar tipo de dispositivo</option>
                        @foreach ($dispositivos as $disp)
                            <option value="{{ $disp->id }}"
                                {{ old('tipo_dispositivo_id', $registro->tipo_dispositivo_id) == $disp->id ? 'selected' : '' }}>
                                {{ $disp->tipo_dispositivo }}
                            </option>
                        @endforeach
                    </select>
                    @error('tipo_dispositivo_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Dirección IP</label>
                    <input type="text" name="ip" id="ip"
                           class="form-control rounded-pill @error('ip') is-invalid @enderror"
                           value="{{ old('ip', $registro->ip) }}" required>
                    @error('ip') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Dirección MAC (Opcional)</label>
                    <input type="text" name="mac"
                           class="form-control rounded-pill @error('mac') is-invalid @enderror"
                           value="{{ old('mac', $registro->mac) }}">
                    @error('mac') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Número de Serie (Opcional)</label>
                    <input type="text" name="numero_serie"
                           class="form-control rounded-pill @error('numero_serie') is-invalid @enderror"
                           value="{{ old('numero_serie', $registro->numero_serie) }}">
                    @error('numero_serie') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Descripción</label>
                    <input type="text" name="descripcion"
                           class="form-control rounded-pill @error('descripcion') is-invalid @enderror"
                           value="{{ old('descripcion', $registro->descripcion) }}">
                    @error('descripcion') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Responsable</label>
                    <input type="text" name="responsable"
                           class="form-control rounded-pill @error('responsable') is-invalid @enderror"
                           value="{{ old('responsable', $registro->responsable) }}" required>
                    @error('responsable') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Dependencia</label>
                    <select name="dependencia_id"
                            class="form-select rounded-pill @error('dependencia_id') is-invalid @enderror"
                            required>
                        <option value="">Seleccionar dependencia</option>
                        @foreach ($dependencias as $dep)
                            <option value="{{ $dep->id }}"
                                {{ old('dependencia_id', $registro->dependencia_id) == $dep->id ? 'selected' : '' }}>
                                {{ $dep->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('dependencia_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="d-flex justify-content-center gap-2 mt-4">
                    <button type="submit" class="btn btn-success rounded-pill px-4">
                        <i class="bi bi-save me-1"></i> Actualizar
                    </button>
                    <a href="{{ route('registros.ocupadas') }}" class="btn btn-secondary rounded-pill px-4">
                        <i class="bi bi-arrow-left"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const redSelect = document.getElementById('red_id');
    const segmentoSelect = document.getElementById('segmento_id');
    const ipInput = document.getElementById('ip');
    const segmentoContainer = document.getElementById('segmento_container');

    function actualizarSegmento() {
        const selectedRed = redSelect.selectedOptions[0];
        const usaSegmentos = selectedRed?.dataset.usaSegmentos === '1';

        segmentoContainer.style.display = usaSegmentos ? 'block' : 'none';
        if (!usaSegmentos) segmentoSelect.value = '';

    }

    function actualizarIP() {
        const selectedRed = redSelect.selectedOptions[0];
        const base = selectedRed?.dataset.base || '';
        const usaSegmentos = selectedRed?.dataset.usaSegmentos === '1';
        const segText = segmentoSelect.selectedOptions[0]?.dataset.seg || '';

        if (base) {
            ipInput.value = usaSegmentos && segText ? `${base}.${segText}.` : `${base}.`;
        } else {
            ipInput.value = '';
        }
    }

    redSelect.addEventListener('change', function() {
        actualizarSegmento();
        actualizarIP();   

    });

    segmentoSelect.addEventListener('change', actualizarIP);

    actualizarSegmento();

});
</script>
@endpush
@endsection