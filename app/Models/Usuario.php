<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Usuario extends Authenticatable
{
    use SoftDeletes, Notifiable, Searchable;

    protected $fillable = ['nombre', 'codigo', 'password', 'dependencia_id', 'imagen'];

    protected $hidden = ['password'];

    public function roles()
    {
        return $this->belongsToMany(Rol::class, 'rol_usuario');
    }

    public function dependencia()
    {
        return $this->belongsTo(Dependencia::class);
    }

    public function toSearchableArray()
    {
        return [
            'nombre' => $this->nombre,
            'codigo' => $this->codigo,
            'dependencia' => $this->dependencia?->nombre,
        ];
    }


    public function hasPermissionTo($nombrePermiso): bool
    {
        return $this->roles()->whereHas('permisos', function ($query) use ($nombrePermiso) {
             $query->where('nombre_permiso', $nombrePermiso);
            })->exists();
    }
} 