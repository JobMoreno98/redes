<?php

namespace App\Providers;

use App\Models\Permiso;
use App\Models\Usuario;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        Gate::before(function (Usuario $usuario, $ability) {
            if ($usuario->roles()->where('nombre_rol', 'Programador')->exists()) {
                return true;
            }
        });

        try {
            if (Schema::hasTable('permisos')) {
                $permisos = Permiso::all();

                foreach ($permisos as $permiso) {
                    Gate::define($permiso->nombre_permiso, function(Usuario $usuario) use ($permiso) {
                        return $usuario->hasPermissionTo($permiso->nombre_permiso);
                    });
                }
            }
        } catch (\Exception $e) {
            //
        }
    }
}