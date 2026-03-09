<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permiso;

class PermisoSeeder extends Seeder
{
    public function run(): void
    {
        //firstOrCreate para no duplicar permisos si el seeder se corre varias veces

        // Roles
        Permiso::firstOrCreate(['nombre_permiso' => 'ver_roles']);
        Permiso::firstOrCreate(['nombre_permiso' => 'crear_roles']);
        Permiso::firstOrCreate(['nombre_permiso' => 'editar_roles']);
        Permiso::firstOrCreate(['nombre_permiso' => 'eliminar_roles']);

        //Permisos
        Permiso::firstOrCreate(['nombre_permiso' => 'ver_permisos']);

        //Usuarios
        Permiso::firstOrCreate(['nombre_permiso' => 'ver_usuarios']);
        Permiso::firstOrCreate(['nombre_permiso' => 'crear_usuarios']);
        Permiso::firstOrCreate(['nombre_permiso' => 'editar_usuarios']);
        Permiso::firstOrCreate(['nombre_permiso' => 'eliminar_usuarios']);

        //Dependencias
        Permiso::firstOrCreate(['nombre_permiso' => 'ver_dependencias']);
        Permiso::firstOrCreate(['nombre_permiso' => 'crear_dependencias']);
        Permiso::firstOrCreate(['nombre_permiso' => 'editar_dependencias']);
        Permiso::firstOrCreate(['nombre_permiso' => 'eliminar_dependencias']);

        //Redes CRUD
        Permiso::firstOrCreate(['nombre_permiso' => 'ver_redes']);
        Permiso::firstOrCreate(['nombre_permiso' => 'crear_redes']);
        Permiso::firstOrCreate(['nombre_permiso' => 'editar_redes']);
        Permiso::firstOrCreate(['nombre_permiso' => 'eliminar_redes']);

        //Segmentos
        Permiso::firstOrCreate(['nombre_permiso' => 'ver_segmentos']);
        Permiso::firstOrCreate(['nombre_permiso' => 'crear_segmentos']);
        Permiso::firstOrCreate(['nombre_permiso' => 'editar_segmentos']);
        Permiso::firstOrCreate(['nombre_permiso' => 'eliminar_segmentos']);

        //Dispositivos
        Permiso::firstOrCreate(['nombre_permiso' => 'ver_dispositivos']);
        Permiso::firstOrCreate(['nombre_permiso' => 'crear_dispositivos']);
        Permiso::firstOrCreate(['nombre_permiso' => 'editar_dispositivos']);
        Permiso::firstOrCreate(['nombre_permiso' => 'eliminar_dispositivos']);

        //Registros
        Permiso::firstOrCreate(['nombre_permiso' => 'ver_registros']);
        Permiso::firstOrCreate(['nombre_permiso' => 'crear_registros']);
        Permiso::firstOrCreate(['nombre_permiso' => 'editar_registros']);
        Permiso::firstOrCreate(['nombre_permiso' => 'buscar_registros']);
        Permiso::firstOrCreate(['nombre_permiso' => 'eliminar_registros']);
        Permiso::firstOrCreate(['nombre_permiso' => 'ver_registros_eliminados']);
        Permiso::firstOrCreate(['nombre_permiso' => 'restaurar_registros_eliminados']);
        Permiso::firstOrCreate(['nombre_permiso' => 'eliminar_registros_eliminados']);

    }
}