<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mesa extends Model
{
    protected $fillable = [
        'numero',
        'zona',
        'capacidad',
        'tipo',
        'es_vip',
        'forma',
        'coord_x',
        'coord_y',
    ];

    protected $casts = [
        'es_vip' => 'boolean',
        'coord_x' => 'decimal:2',
        'coord_y' => 'decimal:2',
    ];

    public function reservas(): HasMany
    {
        return $this->hasMany(Reserva::class);
    }
}
