@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Editar Proyecto</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('projects.update', $project->id) }}">
                            @csrf
                            @method('PUT')

                            {{-- Campo Nombre --}}
                            <div class="row mb-3">
                                <label for="name" class="col-md-3 col-form-label text-md-end">Nombre:</label>
                                <div class="col-md-8">
                                    <input id="name"
                                           type="text"
                                           class="form-control @error('name') is-invalid @enderror"
                                           name="name"
                                           value="{{ old('name', $project->name) }}"
                                           maxlength="250"
                                           placeholder="Ej: Restaurante, Hotel, Bar..."
                                           required
                                           autofocus>

                                    <small class="form-text text-muted">
                                        Nombre del proyecto o negocio (máximo 250 caracteres)
                                    </small>

                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Campo Descripción --}}
                            <div class="row mb-3">
                                <label for="description" class="col-md-3 col-form-label text-md-end">Descripción:</label>
                                <div class="col-md-8">
                                    <textarea id="description"
                                              class="form-control @error('description') is-invalid @enderror"
                                              name="description"
                                              rows="4"
                                              maxlength="500"
                                              placeholder="Descripción opcional del proyecto...">{{ old('description', $project->description) }}</textarea>

                                    <small class="form-text text-muted">
                                        Opcional: Agrega detalles sobre este proyecto (máximo 500 caracteres)
                                    </small>

                                    @error('description')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Información del proyecto --}}
                            <div class="row mb-4">
                                <div class="col-md-8 offset-md-3">
                                    <div class="alert alert-secondary">
                                        <strong>Información:</strong> Este proyecto fue creado el {{ $project->created_at->format('d/m/Y') }}.
                                        @if($project->transactions()->count() > 0)
                                            <br>Tiene <strong>{{ $project->transactions()->count() }}</strong>
                                            {{ $project->transactions()->count() == 1 ? 'transacción asociada' : 'transacciones asociadas' }}.
                                        @else
                                            <br>Aún no tiene transacciones asociadas.
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Botones de acción --}}
                            <div class="row mb-0">
                                <div class="col-md-8 offset-md-3">
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('projects.index') }}" class="btn btn-secondary">
                                            Cancelar
                                        </a>
                                        <button type="submit" class="btn btn-warning">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square me-1" viewBox="0 0 16 16">
                                                <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                                <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                                            </svg>
                                            Actualizar Proyecto
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
