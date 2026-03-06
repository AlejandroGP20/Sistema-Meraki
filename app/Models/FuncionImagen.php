<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FuncionImagen extends Model
{
    protected $table = 'funcion_imagenes';

    protected $fillable = [
        'funcion_id',
        'ruta',
        'orden',
    ];

    public function funcion(): BelongsTo
    {
        return $this->belongsTo(Funcion::class);
    }
}
