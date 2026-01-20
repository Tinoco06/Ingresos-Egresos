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
                                            <i class="bi bi-pencil-square me-1"></i>
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
