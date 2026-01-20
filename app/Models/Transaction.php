<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User; // Se importa el modelo de usuario para la relación

class Transaction extends Model
{
    use SoftDeletes; // Habilita soft deletes

    // Modelo de Transacción
    protected $fillable = [
        'user_id',
        'project_id',
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

    // Cada transacción pertenece a un proyecto
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Scope para aplicar filtros de proyecto, mes, año y búsqueda
     */
    public function scopeApplyFilters($query, ?int $projectId, ?int $month, ?int $year, ?string $search = null)
    {
        return $query
            ->when($projectId, fn($q) => $q->where('project_id', $projectId))
            ->when($month, fn($q) => $q->whereMonth('date', $month))
            ->when($year, fn($q) => $q->whereYear('date', $year))
            ->when($search, fn($q) => $q->where('description', 'like', "%{$search}%"));
    }
}
