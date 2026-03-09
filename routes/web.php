<?php

use App\Http\Controllers\RolController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SegmentoController;
use App\Http\Controllers\DependenciaController;
use App\Http\Controllers\DispositivoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\RegistroController;
use App\Http\Controllers\RedController;
use App\Http\Controllers\PermisoController;


Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::middleware('auth')->group(function () {

    Route::get('/', function () {
        return view('index');
    })->name('inicio');


    // --- Segmentos ---
    Route::prefix('segmentos')->name('segmentos.')->middleware('auth')->group(function () {
        Route::get('/', [SegmentoController::class, 'index'])->name('index')->middleware('can:ver_segmentos');
        Route::get('/create', [SegmentoController::class, 'create'])->name('create')->middleware('can:crear_segmentos');
        Route::post('/', [SegmentoController::class, 'store'])->name('store')->middleware('can:crear_segmentos');
        Route::get('/{segmento}/edit', [SegmentoController::class, 'edit'])->name('edit')->middleware('can:editar_segmentos');
        Route::put('/{segmento}', [SegmentoController::class, 'update'])->name('update')->middleware('can:editar_segmentos');
        Route::delete('/{segmento}', [SegmentoController::class, 'destroy'])->name('destroy')->middleware('can:eliminar_segmentos');
    });
    
    // --- Dependencias ---
    Route::prefix('dependencias')->name('dependencias.')->middleware('auth')->group(function () {
        Route::get('/', [DependenciaController::class, 'index'])->name('index')->middleware('can:ver_dependencias');
        Route::get('/create', [DependenciaController::class, 'create'])->name('create')->middleware('can:crear_dependencias');
        Route::post('/', [DependenciaController::class, 'store'])->name('store')->middleware('can:crear_dependencias');
        Route::get('/{dependencia}/edit', [DependenciaController::class, 'edit'])->name('edit')->middleware('can:editar_dependencias');
        Route::put('/{dependencia}', [DependenciaController::class, 'update'])->name('update')->middleware('can:editar_dependencias');
        Route::delete('/{dependencia}', [DependenciaController::class, 'destroy'])->name('destroy')->middleware('can:eliminar_dependencias');
    });

    // --- Dispositivos ---
    Route::prefix('dispositivos')->name('dispositivos.')->middleware('auth')->group(function () {
        Route::get('/', [DispositivoController::class, 'index'])->name('index')->middleware('can:ver_dispositivos');
        Route::get('/create', [DispositivoController::class, 'create'])->name('create')->middleware('can:crear_dispositivos');
        Route::post('/', [DispositivoController::class, 'store'])->name('store')->middleware('can:crear_dispositivos');
        Route::get('/{dispositivo}/edit', [DispositivoController::class, 'edit'])->name('edit')->middleware('can:editar_dispositivos');
        Route::put('/{dispositivo}', [DispositivoController::class, 'update'])->name('update')->middleware('can:editar_dispositivos');
        Route::delete('/{dispositivo}', [DispositivoController::class, 'destroy'])->name('destroy')->middleware('can:eliminar_dispositivos');
    });

    // --- Usuarios ---
    Route::prefix('usuarios')->name('usuarios.')->middleware('auth')->group(function () {
        Route::get('/', [UsuarioController::class, 'index'])->name('index')->middleware('can:ver_usuarios');
        Route::get('/create', [UsuarioController::class, 'create'])->name('create')->middleware('can:crear_usuarios');
        Route::post('/', [UsuarioController::class, 'store'])->name('store')->middleware('can:crear_usuarios');
        Route::get('/{usuario}/edit', [UsuarioController::class, 'edit'])->name('edit')->middleware('can:editar_usuarios');
        Route::put('/{usuario}', [UsuarioController::class, 'update'])->name('update')->middleware('can:editar_usuarios');
        Route::delete('/{usuario}', [UsuarioController::class, 'destroy'])->name('destroy')->middleware('can:eliminar_usuarios');
    });
    
    // --- Redes ---
    Route::prefix('redes')->name('redes.')->middleware('auth')->group(function () {
        Route::get('/', [RedController::class, 'index'])->name('index')->middleware('can:ver_redes');
        Route::get('/create', [RedController::class, 'create'])->name('create')->middleware('can:crear_redes');
        Route::post('/', [RedController::class, 'store'])->name('store')->middleware('can:crear_redes');
        Route::get('/{rede}/edit', [RedController::class, 'edit'])->name('edit')->middleware('can:editar_redes');
        Route::put('/{rede}', [RedController::class, 'update'])->name('update')->middleware('can:editar_redes');
        Route::delete('/{rede}', [RedController::class, 'destroy'])->name('destroy')->middleware('can:eliminar_redes');
    });

    // --- Roles ---
    Route::prefix('roles')->name('roles.')->middleware('auth')->group(function () {
        Route::get('/', [RolController::class, 'index'])->name('index')->middleware('can:ver_roles');
        Route::get('/create', [RolController::class, 'create'])->name('create')->middleware('can:crear_roles');
        Route::post('/', [RolController::class, 'store'])->name('store')->middleware('can:crear_roles');
        Route::get('/{rol}/edit', [RolController::class, 'edit'])->name('edit')->middleware('can:editar_roles');
        Route::put('/{rol}', [RolController::class, 'update'])->name('update')->middleware('can:editar_roles');
        Route::delete('/{rol}', [RolController::class, 'destroy'])->name('destroy')->middleware('can:eliminar_roles');
    });

    Route::get('permisos', [PermisoController::class, 'index'])
         ->name('permisos.index')
         ->middleware('can:ver_permisos');



    // Vistas de Listado y Búsqueda
    Route::get('registros/ocupadas', [RegistroController::class, 'ocupadas'])->name('registros.ocupadas')->middleware('can:ver_registros');
    Route::get('registros/disponibles', [RegistroController::class, 'disponibles'])->name('registros.disponibles')->middleware('can:ver_registros');
    Route::get('registros/buscar', [RegistroController::class, 'buscar'])->name('registros.buscar')->middleware('can:buscar_registros');
    
    // Vistas de Eliminados (Soft Deletes)
    Route::get('registros/eliminadas', [RegistroController::class, 'eliminadas'])->name('registros.eliminadas')->middleware('can:ver_registros_eliminados');
    Route::post('registros/{id}/restore', [RegistroController::class, 'restore'])->name('registros.restore')->middleware('can:restaurar_registros_eliminados');
    Route::delete('registros/{id}/forzar', [RegistroController::class, 'forceDestroy'])->name('registros.forceDestroy')->middleware('can:eliminar_registros_eliminados');

    // Vistas de Formulario personalizadas
    Route::get('registros/usar', [RegistroController::class, 'usar'])->name('registros.usar')->middleware('can:crear_registross'); // (con 'ss')
    
    Route::get('registros/modificar', function () {
        return view('registros.modificar');
    })->name('registros.modificar')->middleware('can:editar_registros');
    
    Route::post('registros/modificar', [RegistroController::class, 'modificar'])->name('registros.modificar.post')->middleware('can:editar_registros');

    Route::get('registros/eliminar', function() {
        return view('registros.eliminar');
    })->name('registros.eliminar')->middleware('can:eliminar_registros');

    Route::post('registros/eliminar', [RegistroController::class, 'eliminar'])->name('registros.eliminar.post')->middleware('can:eliminar_registros');
    

    Route::get('registros/create', [RegistroController::class, 'create'])
         ->name('registros.create')
         ->middleware('can:crear_registros');

    Route::post('registros', [RegistroController::class, 'store'])
         ->name('registros.store')
         ->middleware('can:crear_registros');

    Route::get('registros/{id}/edit', [RegistroController::class, 'edit'])
         ->name('registros.edit')
         ->middleware('can:editar_registros');
         
    Route::put('registros/{id}', [RegistroController::class, 'update'])
         ->name('registros.update')
         ->middleware('can:editar_registros');

    Route::delete('registros/{id}/destroy', [RegistroController::class, 'destroy'])
         ->name('registros.destroy')
         ->middleware('can:eliminar_registros');
    

});