<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Laravel\Scout\Searchable;

class Segmento extends Model
{
    use SoftDeletes, Searchable;

    protected $fillable = ['segmento', 'red_id'];

    public function registros()
    {
        return $this->hasMany(Registro::class);
    }

    public function red()
    {
        return $this->belongsTo(Red::class);
    }

    public function toSearchableArray()
    {
        return [
            'segmento' => $this->segmento,
            'red' => $this->red?->direccion_base,
        ];
    }
}


