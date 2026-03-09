@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card p-4 shadow-lg">
            <h3 class="text-center mb-4">
                <i class="bi bi-plus-circle me-1"></i> Agregar Nuevo Segmento
            </h3>

            <form method="POST" action="{{ route('segmentos.store') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Número de Segmento</label>
                    <input type="text"
                           name="segmento"
                           class="form-control rounded-pill @error('segmento') is-invalid @enderror"
                           value="{{ old('segmento') }}"
                           maxlength="3"
                           required>
                    @error('segmento')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Red</label>
                    <select name="red_id"
                            class="form-select rounded-pill @error('red_id') is-invalid @enderror"
                            required>
                        <option value="">Seleccionar red</option>
                        @foreach ($reds as $red)
                            <option value="{{ $red->id }}" {{ old('red_id') == $red->id ? 'selected' : '' }}>
                                {{ $red->direccion_base }} — {{ $red->descripcion }}
                            </option>
                        @endforeach
                    </select>
                    @error('red_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-center gap-2">
                    <button type="submit" class="btn btn-success rounded-pill px-4">
                        <i class="bi bi-floppy2-fill"></i> Guardar
                    </button>

                    <a href="{{ route('segmentos.index') }}" class="btn btn-success rounded-pill px-4">
                        <i class="bi bi-card-list"></i> Ver Listado
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
