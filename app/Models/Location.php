<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Tabla: ubicaciones (locations).
 * Lugar dentro del almacén donde está el ítem, como un pasillo o la bodega.
 * El código identifica esa zona, por ejemplo A-01.
 */
class Location extends Model
{
    /** @use HasFactory<\Database\Factories\LocationFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }
}
