<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    /**
     * Determine if the user can view any projects.
     */
    public function viewAny(User $user): bool
    {
        // Cualquier usuario autenticado puede ver la lista
        return true;
    }

    /**
     * Determine if the user can view the project.
     */
    public function view(User $user, Project $project): bool
    {
        // El usuario solo puede ver sus propios proyectos
        return $user->id === $project->user_id;
    }

    /**
     * Determine if the user can create projects.
     */
    public function create(User $user): bool
    {
        // Cualquier usuario autenticado puede crear proyectos
        return true;
    }

    /**
     * Determine if the user can update the project.
     */
    public function update(User $user, Project $project): bool
    {
        // El usuario solo puede actualizar sus propios proyectos
        return $user->id === $project->user_id;
    }

    /**
     * Determine if the user can delete the project.
     */
    public function delete(User $user, Project $project): bool
    {
        // El usuario solo puede eliminar sus propios proyectos
        return $user->id === $project->user_id;
    }
}