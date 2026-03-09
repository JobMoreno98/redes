@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card p-4 shadow-lg">
            <h3 class="text-center mb-4">
               <i class="bi bi-pencil-square"></i> Editar Dependencia
            </h3>

            <form method="POST" action="{{ route('dependencias.update', $dependencia->id) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Nombre de la Dependencia</label>
                    <input type="text" 
                           name="nombre" 
                           class="form-control rounded-pill @error('nombre') is-invalid @enderror" 
                           maxlength="100" 
                           value="{{ old('nombre', $dependencia->nombre) }}" 
                           required>
                    @error('nombre')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="text-center d-flex justify-content-center gap-2">
                    <button type="submit" class="btn btn-success rounded-pill px-4">
                       <i class="bi bi-box-arrow-in-up"></i> Actualizar
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
