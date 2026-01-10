@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Formulario para transacciones</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('transactions.update', $transaction->id) }}">
                            @csrf
                            @method('PUT')

                            {{-- Campo Proyecto --}}
                            <div class="row mb-3">
                                <label for="project_id" class="col-md-3 col-form-label text-md-end">Proyecto:</label>
                                <div class="col-md-6">
                                    <select id="project_id" name="project_id" class="form-select @error('project_id') is-invalid @enderror" required>
                                        <option value="">Selecciona un proyecto</option>
                                        @foreach($projects as $project)
                                            <option value="{{ $project->id }}" {{ old('project_id', $transaction->project_id) == $project->id ? 'selected' : '' }}>
                                                {{ $project->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="form-text text-muted">
                                        Cada transacción debe estar asociada a un proyecto
                                    </small>
                                    @error('project_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Campo Tipo --}}
                            <div class="row mb-3">
                                <label for="type" class="col-md-3 col-form-label text-md-end">Tipo:</label>
                                <div class="col-md-6">
                                    <select id="type" name="type" class="form-select @error('type') is-invalid @enderror" required autofocus>
                                        <option value="">Selecciona un tipo</option>
                                        <option value="ingreso" {{ old('type', $transaction->type) == 'ingreso' ? 'selected' : '' }}>
                                            Ingreso
                                        </option>
                                        <option value="egreso" {{ old('type', $transaction->type) == 'egreso' ? 'selected' : '' }}>
                                            Egreso
                                        </option>
                                    </select>
                                    @error('type')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="description" class="col-md-3 col-form-label text-md-end">Descripción:</label>
                                    <div class="col-md-6">
                                        <!--Mostrar el valor que viene desde la pantalla anterior-->
                                        <input id="description" type="text" class="form-control @error('description') is-invalid @enderror" name="description" value="{{ old('description', $transaction->description) }}" minlength="3" required autofocus>
                                        @error('description')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mb-3">
                                <label for="amount" class="col-md-3 col-form-label text-md-end">Monto:</label>
                                    <div class="col-md-6">
                                        <input id="amount" type="number" class="form-control @error('amount') is-invalid @enderror" name="amount" value="{{ old('amount', $transaction->amount) }}" step="0.01" min="0.01" placeholder="0.00" required autofocus>
                                        @error('amount')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mb-3">
                                <label for="date" class="col-md-3 col-form-label text-md-end">Fecha:</label>
                                    <div class="col-md-6">
                                        <input id="date" type="date" class="form-control @error('date') is-invalid @enderror" name="date" value="{{ old('date', $transaction->date->format('Y-m-d')) }}" min="2000-01-01"max="2099-12-31" required autofocus>
                                        @error('date')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mb-0">
                                    <div class="col-md-6 offset-md-3">
                                        <div class="d-flex justify-content-between">
                                            <a href="{{ route('transactions.index') }}" class="btn btn-secondary">
                                                Cancelar
                                            </a>
                                            <button type="submit" class="btn btn-primary">
                                                Guardar Transacción
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
@endsection
