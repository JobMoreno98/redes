<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Usuario;
use App\Models\Rol;
use Illuminate\Support\Facades\Hash;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $programadorRol = Rol::where('nombre_rol', 'Programador')->first();

        if (!$programadorRol) {
            $this->command->error('No se encontró el rol "Programador". Ejecuta el RolSeeder primero.');
            return;
        }

        $programadorUsuario = Usuario::firstOrCreate(
            ['codigo' => '000123450'], 
            [
                'nombre' => 'Job',
                'password' => Hash::make('cambiarlacontrasena123'),
                'dependencia_id' => null
            ]
        );

        $programadorUsuario->roles()->sync($programadorRol->id);
    }
}