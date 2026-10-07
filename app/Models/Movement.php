<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Tabla: movimientos (movements).
 * Entrada o salida de un ítem. Registra quién la hizo, la cantidad y una nota.
 * El tipo usa las constantes entrada y salida.
 */
class Movement extends Model
{
    /** @use HasFactory<\Database\Factories\MovementFactory> */
    use HasFactory;

    public const ENTRADA = 'entrada';

    public const SALIDA = 'salida';

    protected $fillable = [
        'item_id',
        'user_id',
        'type',
        'quantity',
        'note',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
