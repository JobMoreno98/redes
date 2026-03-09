@extends('layouts.app')

@section('title', 'Buscar IP para Editar')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card p-4 shadow-lg">
            <h3 class="text-center mb-4">
                <i class="bi bi-pencil-square me-1"></i> Buscar IP para Editar
            </h3>

            <form method="POST" action="{{ route('registros.modificar.post') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Dirección IP</label>
                    <input type="text" name="ip" class="form-control rounded-pill @error('ip') is-invalid @enderror"
                           placeholder="Ej: 192.168.1.10" value="{{ old('ip', request()->get('ip')) }}" required>
                    @error('ip') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="d-flex justify-content-center gap-2">
                    <button type="submit" class="btn btn-success rounded-pill px-4">
                        <i class="bi bi-arrow-right-circle me-1"></i> Ir a Editar
                    </button>
                    <a href="{{ route('registros.ocupadas') }}" class="btn btn-success rounded-pill px-4">
                        <i class="bi bi-card-list"></i> Ver Listado
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection