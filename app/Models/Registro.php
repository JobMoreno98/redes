<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
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

    protected static function booted()
    {
        static::saving(fn($r) => $r->ip_long = ip2long($r->ip));
    }

    public function scopeBuscar(Builder $query, ?string $buscar, bool $conEliminados = false): Builder
    {
        if (blank($buscar)) {
            return $query;
        }

        if (filter_var($buscar, FILTER_VALIDATE_IP)) {
            return $query->where('ip', $buscar);
        }

        if (preg_match('/^([0-9A-F]{2}[:-]){5}[0-9A-F]{2}$/i', $buscar)) {
            return $query->where('mac', $buscar);
        }

        $busqueda = static::search($buscar);

        if ($conEliminados) {
            $busqueda->withTrashed();
        }

        $ids = $busqueda->keys();

        return $query->where(function (Builder $q) use ($ids, $buscar) {
            $q->whereIn('id', $ids)
                ->orWhere('numero_serie', $buscar);
        });
    }

    /**
     * Ordena por IP (numérico), antigüedad o, por defecto, la columna indicada (desc).
     */
    public function scopeOrdenarPor(Builder $query, string $orden, string $columnaPorDefecto = 'created_at'): Builder
    {
        // Usa la columna indexada `ip_long`, así MySQL puede ordenar con el índice.
        return match ($orden) {
            'antiguo' => $query->orderBy('created_at', 'asc'),
            'asc'     => $query->orderBy('ip_long', 'asc'),
            'desc'    => $query->orderBy('ip_long', 'desc'),
            default   => $query->orderBy($columnaPorDefecto, 'desc'),
        };
    }
}
