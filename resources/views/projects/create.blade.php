@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Crear Nuevo Proyecto</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('projects.store') }}">
                            @csrf

                            {{-- Campo Nombre --}}
                            <div class="row mb-3">
                                <label for="name" class="col-md-3 col-form-label text-md-end">Nombre:</label>
                                <div class="col-md-8">
                                    <input id="name"
                                           type="text"
                                           class="form-control @error('name') is-invalid @enderror"
                                           name="name"
                                           value="{{ old('name') }}"
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
                                              placeholder="Descripción opcional del proyecto...">{{ old('description') }}</textarea>

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

                            {{-- Información adicional --}}
                            <div class="row mb-4">
                                <div class="col-md-8 offset-md-3">
                                    <div class="alert alert-info">
                                        <strong>Tip:</strong> Una vez creado el proyecto, podrás asignarle transacciones para llevar un control separado de cada negocio.
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
                                        <button type="submit" class="btn btn-primary">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle me-1" viewBox="0 0 16 16">
                                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                                                <path d="m10.97 4.97-.02.022-3.473 4.425-2.093-2.094a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05"/>
                                            </svg>
                                            Guardar Proyecto
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
