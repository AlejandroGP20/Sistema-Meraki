<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reserva extends Model
{
    protected $fillable = [
        'codigo_reserva',
        'user_id',
        'funcion_id',
        'mesa_id',
        'num_personas',
        'estado',
        'monto_total',
        'incluye_cena',
        'es_vip',
        'notas',
        'check_in_at',
        'sillas_reservadas',
        'mesa_completa',
    ];

    protected $casts = [
        'monto_total' => 'decimal:2',
        'incluye_cena' => 'boolean',
        'es_vip' => 'boolean',
        'check_in_at' => 'datetime',
        'sillas_reservadas' => 'array',
        'mesa_completa' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function funcion(): BelongsTo
    {
        return $this->belongsTo(Funcion::class);
    }

    public function mesa(): BelongsTo
    {
        return $this->belongsTo(Mesa::class);
    }
}
