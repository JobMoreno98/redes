@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card p-4 shadow-lg">
            <h3 class="text-center mb-4">
                <i class="bi bi-plus-circle me-1"></i> Agregar Nuevo Usuario
            </h3>

            <form method="POST" action="{{ route('usuarios.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Nombre</label>
                    <input type="text"
                           name="nombre"
                           class="form-control rounded-pill @error('nombre') is-invalid @enderror"
                           maxlength="100"
                           value="{{ old('nombre') }}"
                           required>
                    @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Código</label>
                    <input type="number"
                           name="codigo"
                           class="form-control rounded-pill @error('codigo') is-invalid @enderror"
                           value="{{ old('codigo') }}"
                           required>
                    @error('codigo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Contraseña</label>
                    <input type="password"
                           name="password"
                           class="form-control rounded-pill @error('password') is-invalid @enderror"
                           minlength="6"
                           required>
                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Confirmar Contraseña</label>
                    <input type="password"
                           name="password_confirmation"
                           class="form-control rounded-pill"
                           minlength="6"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Dependencia</label>
                    <select name="dependencia_id"
                            class="form-select rounded-pill @error('dependencia_id') is-invalid @enderror"
                            required>
                        <option value="">Selecciona una dependencia</option>
                        @foreach($dependencias as $dep)
                            <option value="{{ $dep->id }}" {{ old('dependencia_id') == $dep->id ? 'selected' : '' }}>
                                {{ $dep->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('dependencia_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Roles Asignados</label>
                    <div class="card p-3 rounded-4" style="max-height: 250px; overflow-y: auto;">
                        @foreach($roles as $rol)
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       name="roles[]" 
                                       value="{{ $rol->id }}" 
                                       id="rol_{{ $rol->id }}"
                                       {{ ( is_array(old('roles')) && in_array($rol->id, old('roles')) ) ? 'checked' : '' }}>
                                <label class="form-check-label" for="rol_{{ $rol->id }}">
                                    {{ $rol->nombre_rol }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                    @error('roles') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>
                <div class="d-flex justify-content-center gap-2">
                    <button type="submit" class="btn btn-success rounded-pill px-4">
                        <i class="bi bi-floppy2-fill"></i> Guardar
                    </button>
                    <a href="{{ route('usuarios.index') }}" class="btn btn-success rounded-pill px-4">
                        <i class="bi bi-card-list"></i> Ver Listado
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection