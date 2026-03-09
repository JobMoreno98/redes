<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Rol extends Model
{
    use SoftDeletes;
    
    protected $table = 'roles';
    protected $fillable = ['nombre_rol'];

    public function usuarios() {
        return $this->belongsToMany(Usuario::class,'rol_usuario');
    }

    public function permisos() {
        return $this->belongsToMany(Permiso::class,'permiso_rol');
    }
}
