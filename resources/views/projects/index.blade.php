@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            {{-- Card principal de proyectos --}}
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary me-3" title="Volver al inicio">
                            <i class="bi bi-house-door-fill"></i>
                        </a>
                        <h5 class="mb-0">Mis Proyectos</h5>
                    </div>
                    <a href="{{ route('projects.create') }}" class="btn btn-primary" title="Nuevo Proyecto">
                        <i class="bi bi-plus-circle"></i>
                        <span class="d-none d-md-inline">Nuevo</span>
                    </a>
                </div>
                <div class="card-body">
                    @if($projects->count() > 0)
                        <div class="row g-3">
                            @foreach($projects as $project)
                                <div class="col-md-4">
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
                                                <form id="delete-project-{{ $project->id }}"
                                                      action="{{ route('projects.destroy', $project->id) }}"
                                                      method="POST"
                                                      style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button"
                                                            class="btn btn-sm btn-outline-danger"
                                                            title="Eliminar proyecto"
                                                            onclick="confirmDeleteProject({{ $project->id }}, '{{ $project->name }}', {{ $project->transactions_count }})">
                                                        <i class="bi bi-trash"></i> Eliminar
                                                    </button>
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

@push('scripts')
<script>
    function confirmDeleteProject(projectId, projectName, transactionsCount) {
        if (transactionsCount > 0) {
            Swal.fire({
                icon: 'error',
                title: 'No se puede eliminar',
                html: `El proyecto <strong>${projectName}</strong> tiene <strong>${transactionsCount}</strong> ${transactionsCount === 1 ? 'transacción asociada' : 'transacciones asociadas'}.<br><br><small>Debes eliminar o mover las transacciones a otro proyecto antes de eliminar este.</small>`,
                confirmButtonColor: '#dc3545'
            });
        } else {
            Swal.fire({
                title: '¿Eliminar proyecto?',
                html: `¿Estás seguro de eliminar el proyecto <strong>${projectName}</strong>?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-project-' + projectId).submit();
                }
            });
        }
    }
</script>
@endpush
@endsection
