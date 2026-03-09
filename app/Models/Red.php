<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Red extends Model
{
    use SoftDeletes, Searchable;

    protected $table = 'redes';
    public $timestamps = false;

    protected $fillable = [
        'direccion_base',
        'descripcion',
        'usa_segmentos',
        'hosts_reservados',
    ];

    protected $casts = [
        'usa_segmentos' => 'boolean',
        'hosts_reservados' => 'array',
    ];

    public function registros()
    {
        return $this->hasMany(Registro::class);
    }

    public function dispositivos()
    {
        return $this->hasMany(Dispositivo::class);
    }

    public function segmentos()
    {
        return $this->hasMany(Segmento::class);
    }

// Devuelve la dirección completa para mostrarla en las vistas.
    public function getDireccionCompletaAttribute()
    {
        $octetos = explode('.', $this->direccion_base);

        // si usa segmentos, completa a 148.202.0.0
        if ($this->usa_segmentos) {
            while (count($octetos) < 2) {
                $octetos[] = '0';
            }
            $octetos = array_slice($octetos, 0, 2);
            return implode('.', $octetos) . '.0.0';
        }

        // si no usa segmentos, completa a 148.202.50.0

        while (count($octetos) < 3) {
            $octetos[] = '0';
        }
        $octetos = array_slice($octetos, 0, 3);
        return implode('.', $octetos) . '.0';
    }


    
    //Accessor para mostrar los hosts como string en los formularios
    public function getHostsReservadosStringAttribute()
    {
        //Si el valor es null o un array vacío, devuelve un string vacío
        if (empty($this->hosts_reservados)) {
            return '';
        }
        //Convierte [126, 127, 128] a "126, 127, 128"
        return implode(', ', $this->hosts_reservados);
    }

    public function toSearchableArray()
    {
        return [
            'direccion_base' => $this->direccion_base,
            'descripcion' => $this->descripcion,
        ];
    }
}
