<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use App\Models\Red;

class Dispositivo extends Model
{
    use SoftDeletes, Searchable;

    protected $fillable = [
        'tipo_dispositivo',
        'red_id'
    ];

    public function registros()
    {
        return $this->hasMany(Registro::class, 'tipo_dispositivo_id');
    }

    public function red()
    {
        return $this->belongsTo(Red::class);
    }

    public function toSearchableArray()
    {
        return [
            'tipo_dispositivo' => $this->tipo_dispositivo,
        ];
    }
}



