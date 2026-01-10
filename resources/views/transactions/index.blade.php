@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- Transacciones --}}
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary me-3" title="Volver al inicio">
                            <i class="bi bi-house-door-fill"></i>
                        </a>
                        <h5 class="mb-0">Mis transacciones</h5>
                    </div>
                    <a href="{{ route('transactions.create')}}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Nueva Transacción
                    </a>
                </div>
                <div class="card-body">
                    {{-- Filtro de proyectos --}}
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <form method="GET" action="{{ route('transactions.index') }}">
                                <div class="input-group">
                                    <label class="input-group-text" for="project_filter">
                                        Proyecto
                                    </label>
                                    <select class="form-select" id="project_filter" name="project" onchange="this.form.submit()">
                                        <option value="">Todos los proyectos</option>
                                        @foreach($projects as $project)
                                            <option value="{{ $project->id }}" {{ request('project') == $project->id ? 'selected' : '' }}>
                                                {{ $project->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </form>
                        </div>
                        @if(request('project'))
                            <div class="col-md-8">
                                <a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-x-circle"></i> Limpiar filtro
                                </a>
                            </div>
                        @endif
                    </div>

                    @if($transactions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Proyecto</th>
                                        <th>Fecha</th>
                                        <th>Descripción</th>
                                        <th>Monto</th>
                                        <th>Tipo</th>
                                        <th width="120">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($transactions as $transaction)
                                        <tr>
                                            <td>
                                                <span class="badge bg-info">
                                                    {{ $transaction->project->name }}
                                                </span>
                                            </td>
                                            <td>{{$transaction->date->format('d/m/Y')}}</td>
                                            <td>{{$transaction->description}}</td>
                                            <td><strong>L{{ number_format($transaction->amount, 2) }}</strong></td>
                                            <td>
                                                @if($transaction->type == 'ingreso')
                                                    <span class="badge bg-success">Ingreso</span>
                                                @else
                                                    <span class="badge bg-danger">Egreso</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('transactions.edit', $transaction->id) }}"
                                                       class="btn btn-sm btn-warning"
                                                       title="Editar">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                                            <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325"/>
                                                        </svg>
                                                    </a>
                                                    <form action="{{ route('transactions.destroy', $transaction->id) }}"
                                                          method="POST"
                                                          style="display:inline;"
                                                          onsubmit="return confirm('¿Estás seguro de eliminar esta transacción?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                                class="btn btn-sm btn-danger"
                                                                title="Eliminar">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                                                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
                                                                <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Paginación --}}
                        <div class="d-flex justify-content-center mt-3">
                            {{ $transactions->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-inbox" style="font-size: 4rem; color: #ccc;"></i>
                            <h5 class="text-muted mt-3">No hay transacciones{{ request('project') ? ' en este proyecto' : '' }}</h5>
                            @if(request('project'))
                                <a href="{{ route('transactions.index') }}" class="btn btn-secondary mt-2">
                                    Ver todas las transacciones
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            {{-- Resumen de las transacciones --}}
            <div class="row mb-4 mt-4">
                <div class="col-md-4">
                    <div class="card text-white bg-success">
                        <div class="card-body">
                            <h5 class="card-title">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-arrow-up-circle" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-7.5 3.5a.5.5 0 0 1-1 0V5.707L5.354 7.854a.5.5 0 1 1-.708-.708l3-3a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 5.707z"/>
                                </svg>
                                Total Ingresos
                            </h5>
                            <h2>L{{ number_format($totalIngresos, 2)}}</h2>
                            @if(request('project') && $projects->find(request('project')))
                                <small>Proyecto: {{ $projects->find(request('project'))->name }}</small>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-danger">
                        <div class="card-body">
                            <h5 class="card-title">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-arrow-down-circle" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v5.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293z"/>
                                </svg>
                                Total Egresos
                            </h5>
                            <h2>L{{ number_format($totalEgresos, 2)}}</h2>
                            @if(request('project') && $projects->find(request('project')))
                                <small>Proyecto: {{ $projects->find(request('project'))->name }}</small>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-primary">
                        <div class="card-body">
                            <h5 class="card-title">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-wallet2" viewBox="0 0 16 16">
                                    <path d="M12.136.326A1.5 1.5 0 0 1 14 1.78V3h.5A1.5 1.5 0 0 1 16 4.5v9a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 13.5v-9a1.5 1.5 0 0 1 1.432-1.499zM5.562 3H13V1.78a.5.5 0 0 0-.621-.484zM1.5 4a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h13a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5z"/>
                                </svg>
                                Balance
                            </h5>
                            <h2>L{{ number_format($balance, 2)}}</h2>
                            @if(request('project') && $projects->find(request('project')))
                                <small>Proyecto: {{ $projects->find(request('project'))->name }}</small>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Gráfica --}}
            <div class="card mb-4 mt-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-bar-chart"></i> Gráfica de Ingresos vs Egresos
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="transactionChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Cargar charts js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Obtener el elemento canvas
    const ctx = document.getElementById('transactionChart').getContext('2d');

    // Crear la gráfica
    const chart = new Chart(ctx, {
        type: 'bar', // grafico de barras
        data: {
            labels: ['Ingresos L', 'Egresos L', 'Balance L'],
            datasets: [{
                label: 'Valores',
                data: [
                    {{ $totalIngresos }},  // Ingresos en dinero
                    {{ $totalEgresos }},   // Egresos en dinero
                    {{ $balance }}         // Balance en dinero
                ],
                backgroundColor: [
                    'rgba(40, 167, 69, 0.7)',   // Verde para ingresos
                    'rgba(220, 53, 69, 0.7)',   // Rojo para egresos
                    'rgba(13, 110, 253, 0.7)'   // Azul para balance
                ],
                borderColor: [
                    'rgba(40, 167, 69, 1)',
                    'rgba(220, 53, 69, 1)',
                    'rgba(13, 110, 253, 1)'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endsection
