@extends('layouts.app')

@section('title', 'Agregar Nuevo Rol')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card p-4 shadow-lg">
            <h3 class="text-center mb-4">
                <i class="bi bi-shield-plus me-1"></i> Agregar Nuevo Rol
            </h3>

            <form method="POST" action="{{ route('roles.store') }}">
                @csrf
                
                <div class="mb-3">
                    <label class="form-label">Nombre del Rol</label>
                    <input type="text"
                           name="nombre_rol"
                           class="form-control rounded-pill @error('nombre_rol') is-invalid @enderror"
                           maxlength="50"
                           value="{{ old('nombre_rol') }}"
                           required>
                    @error('nombre_rol') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Permisos Asignados</label>
                    <div class="card p-3 rounded-4" style="max-height: 400px; overflow-y: auto;">
                        
                        {{-- Bucle exterior para los GRUPOS --}}
                        @foreach($permisosAgrupados as $grupo => $permisosDelGrupo)
                            <div class="mb-3">
                                {{-- Título del Grupo --}}
                                <h6 class="fw-bold text-success mb-1">{{ $grupo }}</h6>
                                
                                {{-- Bucle interior para los PERMISOS --}}
                                @foreach($permisosDelGrupo as $permiso)
                                    <div class="form-check ms-2">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               name="permisos[]" 
                                               value="{{ $permiso->id }}" 
                                               id="permiso_{{ $permiso->id }}"
                                               {{ ( is_array(old('permisos')) && in_array($permiso->id, old('permisos')) ) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="permiso_{{ $permiso->id }}">
                                            {{ Str::ucfirst(Str::replace('_', ' ', $permiso->nombre_permiso)) }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            
                            {{-- Añadir un separador, excepto para el último grupo --}}
                            @if (!$loop->last)
                                <hr class="my-2" style="border-color: rgba(255,255,255,0.2);">
                            @endif

                        @endforeach
                        
                    </div>
                    @error('permisos') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                <div class="d-flex justify-content-center gap-2">
                    <button type="submit" class="btn btn-success rounded-pill px-4">
                        <i class="bi bi-floppy2-fill"></i> Guardar
                    </button>
                    <a href="{{ route('roles.index') }}" class="btn btn-success rounded-pill px-4">
                        <i class="bi bi-card-list"></i> Ver Listado
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection