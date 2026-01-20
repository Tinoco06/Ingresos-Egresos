<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Http\Requests\ProjectRequest;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        $projects = $user->projects()->withCount('transactions')->get();

        return view('projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('projects.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProjectRequest $request)
    {
        auth()->user()->projects()->create($request->validated());

        return redirect()->route('projects.index')->with('success', 'Proyecto creado exitosamente.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        $this->authorize('update', $project);

        return view('projects.edit', compact('project'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProjectRequest $request, Project $project)
    {
        $this->authorize('update', $project);

        $project->update($request->validated());

        return redirect()->route('projects.index')->with('success', 'Proyecto actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $this->authorize('delete', $project);

        // Verificar si el proyecto tiene transacciones activas
        $transactionCount = $project->transactions()->count();

        if ($transactionCount > 0) {
            return redirect()->route('projects.index')
                ->with('error', "No puedes eliminar este proyecto. Tiene {$transactionCount} transacción(es) asociada(s). Elimina o mueve las transacciones primero.");
        }

        $project->delete();

        return redirect()->route('projects.index')->with('success', 'Proyecto eliminado exitosamente.');
    }
}
