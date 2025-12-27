<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User; // Se importa el modelo de usuario para la relación

class Transaction extends Model
{
    // Modelo de Transacción
    protected $fillable = [
        'user_id',
        'type',
        'description',
        'amount',
        'date',
    ];

    // Conversión de atributos, mejor manipulación 
    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2'
    ];

    // Relación con el usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
