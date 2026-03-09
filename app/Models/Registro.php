<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Registro extends Model
{
    use SoftDeletes, Searchable;

    protected $fillable = [
        'ip',
        'mac',
        'numero_serie',
        'descripcion',
        'responsable',
        'dependencia_id',
        'segmento_id',
        'tipo_dispositivo_id',
        'red_id',
    ];


    public function dependencia()
    {
        return $this->belongsTo(Dependencia::class);
    }

    public function segmento()
    {
        return $this->belongsTo(Segmento::class);
    }

    public function tipo_dispositivo()
    {
        return $this->belongsTo(Dispositivo::class, 'tipo_dispositivo_id');
    }

    public function red()
    {
        return $this->belongsTo(Red::class);
    }

    public function toSearchableArray()
    {
        return [
            'ip' => $this->ip,
            'mac' => $this->mac,
            'numero_serie' => $this->numero_serie,
            'descripcion' => $this->descripcion,
            'responsable' => $this->responsable,
            'dependencia' => $this->dependencia,
            'segmento' => $this->segmento?->segmento,
            'dispositivo' => $this->tipo_dispositivo?->tipo_dispositivo,
            'red' => $this->red?->direccion_completa,
        ];
    }

    //Esto asegura que scout:import también indexe los registros eliminados (SoftDelete)
    protected function makeAllSearchableUsing($query)
    {
        return $query->withTrashed();
    }
}
