<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Funcion extends Model
{
    protected $table = 'funciones';

    protected $fillable = [
        'fecha',
        'horario',
        'nombre',
        'artista',
        'genero_musical',
        'descripcion',
        'precio_entrada',
        'precio_entrada_cena',
        'precio_vip',
        'estado',
        'tipo',
        'cupo_maximo',
        'cupo_disponible',
    ];

    protected $casts = [
        'fecha' => 'date',
        'precio_entrada' => 'decimal:2',
        'precio_entrada_cena' => 'decimal:2',
        'precio_vip' => 'decimal:2',
    ];

    protected $appends = ['imagen_principal'];

    public function imagenes(): HasMany
    {
        return $this->hasMany(FuncionImagen::class)->orderBy('orden');
    }

    public function imagenPrincipal()
    {
        return $this->hasOne(FuncionImagen::class)->where('es_principal', true);
    }

    // Accessor para imagen_principal
    public function getImagenPrincipalAttribute()
    {
        return $this->imagenes()->where('es_principal', true)->first();
    }

    public function reservas(): HasMany
    {
        return $this->hasMany(Reserva::class);
    }
}
