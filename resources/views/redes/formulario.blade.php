@extends('layouts.app')

@section('title', 'Agregar Red')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card p-4 shadow-lg">

            <h3 class="mb-4 text-center">
                <i class="bi bi-plus-circle me-1"></i> Agregar Nueva Red
            </h3>

            <form method="POST" action="{{ route('redes.store') }}">
                @csrf

                <div class="mb-3">
                    <label for="direccion_base" class="form-label">Dirección Base</label>
                    <input type="text"
                           name="direccion_base"
                           id="direccion_base"
                           class="form-control rounded-pill @error('direccion_base') is-invalid @enderror"
                           value="{{ old('direccion_base') }}"
                           placeholder="Ej. 148.202.0.0 o 201.202.50.0"
                           required>
                    @error('direccion_base')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <input type="text"
                           name="descripcion"
                           id="descripcion"
                           class="form-control rounded-pill @error('descripcion') is-invalid @enderror"
                           value="{{ old('descripcion') }}">
                    @error('descripcion')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="hosts_reservados" class="form-label">Hosts Reservados (Opcional)</label>
                    <input type="text"
                           name="hosts_reservados"
                           id="hosts_reservados"
                           class="form-control rounded-pill @error('hosts_reservados') is-invalid @enderror"
                           value="{{ old('hosts_reservados') }}"
                           placeholder="Ej. 1, 126, 127, 254">
                    <div class="form-text text-white">
                        Ingresa los últimos octetos separados por comas (ej. 1, 10, 254).
                    </div>
                    @error('hosts_reservados')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-check mb-4">
                    <input type="hidden" name="usa_segmentos" value="0">
                    <input class="form-check-input"
                           type="checkbox"
                           name="usa_segmentos"
                           id="usa_segmentos"
                           value="1"
                           {{ old('usa_segmentos') ? 'checked' : '' }}>
                    <label class="form-check-label" for="usa_segmentos">Usa Segmentos</label>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-success rounded-pill px-4">
                        <i class="bi bi-floppy2-fill"></i> Guardar
                    </button>
                    <a href="{{ route('redes.index') }}" class="btn btn-success rounded-pill px-4">
                        <i class="bi bi-card-list"></i> Ver Listado
                    </a>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
    const checkbox = document.getElementById('usa_segmentos');
    const input = document.getElementById('direccion_base');

    function actualizarPlaceholder() {
        input.placeholder = checkbox.checked
            ? 'Ej. 148.202 o 148.202.0.0'
            : 'Ej. 201.202.50 o 201.202.50.0';
    }

    checkbox.addEventListener('change', actualizarPlaceholder);
    actualizarPlaceholder();
</script>
@endsection