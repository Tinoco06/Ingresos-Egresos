@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            {{-- Mensaje de éxito --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- Card principal de proyectos --}}
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary me-3" title="Volver al inicio">
                            <i class="bi bi-house-door-fill"></i>
                        </a>
                        <h5 class="mb-0">Mis Proyectos</h5>
                    </div>
                    <a href="{{ route('projects.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Nuevo Proyecto
                    </a>
                </div>
                <div class="card-body">
                    @if($projects->count() > 0)
                        <div class="row">
                            @foreach($projects as $project)
                                <div class="col-md-4 mb-4">
                                    <div class="card h-100 shadow-sm border-primary">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-3">
                                                <h5 class="card-title text-primary mb-0">{{ $project->name }}</h5>
                                                <span class="badge bg-secondary">
                                                    {{ $project->transactions_count }}
                                                    {{ $project->transactions_count == 1 ? 'transacción' : 'transacciones' }}
                                                </span>
                                            </div>

                                            @if($project->description)
                                                <p class="card-text text-muted">{{ $project->description }}</p>
                                            @else
                                                <p class="card-text text-muted fst-italic">Sin descripción</p>
                                            @endif

                                            <div class="d-flex justify-content-between align-items-center mt-3">
                                                <small class="text-muted">
                                                    Creado: {{ $project->created_at->format('d/m/Y') }}
                                                </small>
                                            </div>
                                        </div>
                                        <div class="card-footer bg-transparent">
                                            <div class="btn-group w-100" role="group">
                                                <a href="{{ route('transactions.index', ['project' => $project->id]) }}"
                                                   class="btn btn-sm btn-outline-success"
                                                   title="Ver transacciones">
                                                    <i class="bi bi-eye"></i> Ver
                                                </a>
                                                <a href="{{ route('projects.edit', $project->id) }}"
                                                   class="btn btn-sm btn-outline-warning"
                                                   title="Editar proyecto">
                                                    <i class="bi bi-pencil"></i> Editar
                                                </a>
                                                <button type="button"
                                                        class="btn btn-sm btn-outline-danger"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#deleteModal{{ $project->id }}"
                                                        title="Eliminar proyecto">
                                                    <i class="bi bi-trash"></i> Eliminar
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Modal de confirmación para eliminar --}}
                                <div class="modal fade" id="deleteModal{{ $project->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $project->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteModalLabel{{ $project->id }}">Confirmar eliminación</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>¿Estás seguro de que deseas eliminar el proyecto <strong>{{ $project->name }}</strong>?</p>
                                                @if($project->transactions_count > 0)
                                                    <div class="alert alert-warning">
                                                        <strong>Advertencia:</strong> Este proyecto tiene {{ $project->transactions_count }}
                                                        {{ $project->transactions_count == 1 ? 'transacción asociada' : 'transacciones asociadas' }}.
                                                        Al eliminar el proyecto, las transacciones quedarán sin proyecto asignado.
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                <form action="{{ route('projects.destroy', $project->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Sí, eliminar</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        {{-- Estado vacío cuando no hay proyectos --}}
                        <div class="text-center py-5">
                            <i class="bi bi-folder-plus text-primary" style="font-size: 100px;"></i>
                            <h3 class="mb-3 mt-3">¡Comienza a organizar tus negocios!</h3>
                            <p class="text-muted mb-4 fs-5">
                                Los proyectos te permiten separar las transacciones de cada negocio.<br>
                                <strong>Ejemplo:</strong> Restaurante, Hotel, Bar o proyectos personales.
                            </p>
                            <a href="{{ route('projects.create') }}" class="btn btn-primary btn-lg px-5 py-3 shadow">
                                <i class="bi bi-plus-circle-fill me-2"></i>
                                <strong>Crear mi primer proyecto</strong>
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Información adicional --}}
            @if($projects->count() > 0)
                <div class="alert alert-info mt-4">
                    <strong>Nota:</strong> Para crear transacciones asociadas a un proyecto, primero debes tener al menos un proyecto creado.
                    Las transacciones sin proyecto asignado aparecerán como "Sin proyecto" en tus reportes.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
