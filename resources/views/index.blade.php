@extends('layouts.app')

@section('title', 'Inicio - Registro IP')

@section('content')
    <h2 class="text-center mt-5 mb-4">Selecciona una Opción</h2> 

<div class="row g-4 justify-content-center">
    

    @can('crear_registros')
    <div class="col-md-4 col-lg-3">
        <a href="{{ route('registros.create') }}" class="text-decoration-none text-white">
            <div class="card p-4 text-center">
                <i class="bi bi-plus-circle"></i>
                <h5 class="mt-3">Insertar</h5>
            </div>
        </a>
    </div>
    @endcan

    @can('ver_registros')
    <div class="col-md-4 col-lg-3">
        <a href="{{ route('registros.ocupadas') }}" class="text-decoration-none text-white">
            <div class="card p-4 text-center">
                <i class="bi bi-router"></i>
                <h5 class="mt-3">Mostrar Ocupadas</h5>
            </div>
        </a>
    </div>
    @endcan

    @can('ver_registros')
    <div class="col-md-4 col-lg-3">
        <a href="{{ route('registros.disponibles') }}" class="text-decoration-none text-white">
            <div class="card p-4 text-center">
                <i class="bi bi-wifi"></i>
                <h5 class="mt-3">Mostrar Disponibles</h5>
            </div>
        </a>
    </div>
    @endcan

    @can('buscar_registros')
    <div class="col-md-4 col-lg-3">
        <a href="{{ route('registros.buscar') }}" class="text-decoration-none text-white">
            <div class="card p-4 text-center">
                <i class="bi bi-search"></i>
                <h5 class="mt-3">Buscar</h5>
            </div>
        </a>
    </div>
    @endcan

    @can('editar_registros')
    <div class="col-md-4 col-lg-3">
        <a href="{{ route('registros.modificar') }}" class="text-decoration-none text-white">
            <div class="card p-4 text-center">
                <i class="bi bi-pencil-square"></i>
                <h5 class="mt-3">Modificar</h5>
            </div>
        </a>
    </div>
    @endcan

    @can('eliminar_registros')
    <div class="col-md-4 col-lg-3">
        <a href="{{ route('registros.eliminar') }}" class="text-decoration-none text-white">
            <div class="card p-4 text-center">
                <i class="bi bi-trash"></i>
                <h5 class="mt-3">Eliminar</h5>
            </div>
        </a>
    </div>
    @endcan

    @can('ver_registros_eliminados')
    <div class="col-md-4 col-lg-3">
        <a href="{{ route('registros.eliminadas') }}" class="text-decoration-none text-white">
            <div class="card p-4 text-center">
                <i class="bi bi-recycle"></i>
                <h5 class="mt-3">Mostrar Eliminadas</h5>
            </div>
        </a>
    </div>
    @endcan

</div>
  
    @canany(['ver_usuarios', 'ver_roles', 'ver_permisos', 'ver_dependencias', 'ver_redes', 'ver_segmentos', 'ver_dispositivos'])
    
    <h3 class="text-center mt-5 mb-4">Opciones Avanzadas</h3>
    <div class="row g-4 justify-content-center">

        @can('ver_usuarios')
        <div class="col-md-4 col-lg-3">
            <a href="{{ route('usuarios.index') }}" class="text-decoration-none text-white">
                <div class="card p-4 text-center">
                    <i class="bi bi-people-fill"></i>
                    <h5 class="mt-3">Gestionar Usuarios</h5>
                </div>
            </a>
        </div>
        @endcan

        @can('ver_roles')
        <div class="col-md-4 col-lg-3">
            <a href="{{ route('roles.index') }}" class="text-decoration-none text-white">
                <div class="card p-4 text-center">
                    <i class="bi bi-person-lock"></i>
                    <h5 class="mt-3">Roles</h5>
                </div>
            </a>
        </div>
        @endcan

        @can('ver_permisos')
        <div class="col-md-4 col-lg-3">
            <a href="{{ url('/permisos') }}" class="text-decoration-none text-white">
                <div class="card p-4 text-center">
                    <i class="bi bi-shield-lock"></i>
                    <h5 class="mt-3">Permisos</h5>
                </div>
            </a>
        </div>
        @endcan

        @can('ver_dependencias')
        <div class="col-md-4 col-lg-3">
            <a href="{{ route('dependencias.index') }}" class="text-decoration-none text-white">
                <div class="card p-4 text-center">
                    <i class="bi bi-building"></i>
                    <h5 class="mt-3">Dependencias</h5>
                </div>
            </a>
        </div>
        @endcan

        @can('ver_redes')
        <div class="col-md-4 col-lg-3">
            <a href="{{ route('redes.index') }}" class="text-decoration-none text-white">
                <div class="card p-4 text-center">
                    <i class="bi bi-diagram-3"></i>
                    <h5 class="mt-3">Redes</h5>
                </div>
            </a>
        </div>
        @endcan

        @can('ver_segmentos')
        <div class="col-md-4 col-lg-3">
            <a href="{{ route('segmentos.index') }}" class="text-decoration-none text-white">
                <div class="card p-4 text-center">
                    <i class="bi bi-diagram-3-fill"></i>
                    <h5 class="mt-3">Segmentos</h5>
                </div>
            </a>
        </div>
        @endcan

        @can('ver_dispositivos')
        <div class="col-md-4 col-lg-3">
            <a href="{{ route('dispositivos.index') }}" class="text-decoration-none text-white">
                <div class="card p-4 text-center">
                    <i class="bi bi-pc-display"></i>
                    <h5 class="mt-3">Tipos de Dispositivos</h5>
                </div>
            </a>
        </div>
        @endcan

    </div>
    @endcanany

@endsection