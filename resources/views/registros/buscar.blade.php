@extends('layouts.app')

@section('title', 'Buscar Registros')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7 col-lg-5">
        <div class="card p-4 shadow-lg">
            <h3 class="text-center mb-4">
                <i class="bi bi-search me-1"></i> Buscar Registros
            </h3>

            <form method="GET" action="{{ route('registros.ocupadas') }}">
                
                <div class="mb-3">
                    <label class="form-label">Término de búsqueda</label>
                    <input type="text" name="buscar" class="form-control rounded-pill"
                           placeholder="Ej: 192.168.1.10, HP, Erik..." value="{{ old('buscar') }}" required>
                </div>

                <div class="d-flex justify-content-center gap-2">
                    <button type="submit" class="btn btn-success rounded-pill px-4">
                        <i class="bi bi-search me-1"></i> Buscar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection