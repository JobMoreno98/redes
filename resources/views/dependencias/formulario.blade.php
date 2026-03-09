@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card p-4 shadow-lg">
            <h3 class="text-center mb-4">
              <i class="bi bi-plus-circle me-1"></i> Agregar Nueva Dependencia
            </h3>

            <form method="POST" action="{{ route('dependencias.store') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Nombre de la Dependencia</label>
                    <input type="text" 
                           name="nombre" 
                           class="form-control rounded-pill @error('nombre') is-invalid @enderror" 
                           maxlength="100" 
                           value="{{ old('nombre') }}" 
                           required>
                    @error('nombre')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-center gap-2">
                    <button type="submit" class="btn btn-success rounded-pill px-4">
                        <i class="bi bi-floppy2-fill"></i> Guardar
                    </button>

                    <a href="{{ route('dependencias.index') }}" class="btn btn-success rounded-pill px-4">
                        <i class="bi bi-card-list"></i> Ver Listado
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
