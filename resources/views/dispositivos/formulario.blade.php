@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card p-4 shadow-lg">
            <h3 class="text-center mb-4">
                <i class="bi bi-plus-circle me-1"></i> Agregar Nuevo Dispositivo
            </h3>

            <form method="POST" action="{{ route('dispositivos.store') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Red Asociada</label>
                    {{-- La variable $redes se pasa desde DispositivoController@create --}}
                    <select name="red_id" class="form-select rounded-pill @error('red_id') is-invalid @enderror" required>
                        <option value="">Seleccionar red</option>
                        @foreach ($redes as $r)
                            <option value="{{ $r->id }}" {{ old('red_id') == $r->id ? 'selected' : '' }}>
                                {{ $r->direccion_completa }}
                            </option>
                        @endforeach
                    </select>
                    @error('red_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Tipo de Dispositivo</label>
                    <input type="text" 
                           name="tipo_dispositivo" 
                           class="form-control rounded-pill @error('tipo_dispositivo') is-invalid @enderror" 
                           maxlength="100" 
                           value="{{ old('tipo_dispositivo') }}" 
                           required>
                    @error('tipo_dispositivo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-center gap-2">
                    <button type="submit" class="btn btn-success rounded-pill px-4">
                        <i class="bi bi-floppy2-fill"></i> Guardar
                    </button>

                    <a href="{{ route('dispositivos.index') }}" class="btn btn-success rounded-pill px-4">
                        <i class="bi bi-card-list"></i> Ver Listado
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection