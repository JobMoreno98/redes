<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rol;
use App\Models\Permiso;

class RolSeeder extends Seeder
{
    public function run(): void
    {
        $todosLosPermisos = Permiso::pluck('id'); 

        $programadorRol = Rol::firstOrCreate(
            ['nombre_rol' => 'Programador']
        );

        $programadorRol->permisos()->sync($todosLosPermisos);
    }
}