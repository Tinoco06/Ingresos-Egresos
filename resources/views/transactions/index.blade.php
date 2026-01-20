@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            {{-- Transacciones --}}
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary me-3" title="Volver al inicio">
                            <i class="bi bi-house-door-fill"></i>
                        </a>
                        <h5 class="mb-0">Mis transacciones</h5>
                    </div>
                    <div class="d-flex gap-2">
                        @if($transactions->count() > 0)
                            <a href="{{ route('transactions.export', request()->all()) }}" class="btn btn-success" title="Exportar a Excel">
                                <i class="bi bi-file-earmark-excel"></i>
                                <span class="d-none d-md-inline">Exportar</span>
                            </a>
                        @endif
                        <a href="{{ route('transactions.create')}}" class="btn btn-primary" title="Nueva Transacción">
                            <i class="bi bi-plus-circle"></i>
                            <span class="d-none d-md-inline">Nueva</span>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    {{-- Filtros --}}
                    <form method="GET" action="{{ route('transactions.index') }}" id="filterForm">
                        <div class="row g-3 mb-4">
                            {{-- Buscador por descripción --}}
                            <div class="col-12 col-lg-5">
                                <div class="input-group">
                                    <span class="input-group-text bg-white">
                                        <i class="bi bi-search"></i>
                                    </span>
                                    <input type="text"
                                           class="form-control"
                                           name="search"
                                           placeholder="Buscar por descripción..."
                                           value="{{ request('search') }}">
                                    <button type="submit" class="btn btn-outline-primary">
                                        Buscar
                                    </button>
                                </div>
                            </div>

                            {{-- Filtro de proyectos --}}
                            <div class="col-6 col-lg-2">
                                <select class="form-select" id="project_filter" name="project" onchange="this.form.submit()">
                                    <option value="">Proyecto</option>
                                    @foreach($projects as $project)
                                        <option value="{{ $project->id }}" {{ request('project') == $project->id ? 'selected' : '' }}>
                                            {{ $project->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Filtro de mes --}}
                            <div class="col-6 col-lg-2">
                                <select class="form-select" id="month_filter" name="month" onchange="this.form.submit()">
                                    <option value="">Mes</option>
                                    <option value="1" {{ request('month') == '1' ? 'selected' : '' }}>Enero</option>
                                    <option value="2" {{ request('month') == '2' ? 'selected' : '' }}>Febrero</option>
                                    <option value="3" {{ request('month') == '3' ? 'selected' : '' }}>Marzo</option>
                                    <option value="4" {{ request('month') == '4' ? 'selected' : '' }}>Abril</option>
                                    <option value="5" {{ request('month') == '5' ? 'selected' : '' }}>Mayo</option>
                                    <option value="6" {{ request('month') == '6' ? 'selected' : '' }}>Junio</option>
                                    <option value="7" {{ request('month') == '7' ? 'selected' : '' }}>Julio</option>
                                    <option value="8" {{ request('month') == '8' ? 'selected' : '' }}>Agosto</option>
                                    <option value="9" {{ request('month') == '9' ? 'selected' : '' }}>Septiembre</option>
                                    <option value="10" {{ request('month') == '10' ? 'selected' : '' }}>Octubre</option>
                                    <option value="11" {{ request('month') == '11' ? 'selected' : '' }}>Noviembre</option>
                                    <option value="12" {{ request('month') == '12' ? 'selected' : '' }}>Diciembre</option>
                                </select>
                            </div>

                            {{-- Filtro de año --}}
                            <div class="col-6 col-lg-2">
                                <select class="form-select" id="year_filter" name="year" onchange="this.form.submit()">
                                    <option value="">Año</option>
                                    @foreach($years as $year)
                                        <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                                            {{ $year }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Botón limpiar filtros --}}
                            @if(request('project') || request('month') || request('year') || request('search'))
                                <div class="col-6 col-lg-1 d-flex align-items-center">
                                    <a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary w-100" title="Limpiar filtros">
                                        <i class="bi bi-x-lg"></i>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </form>

                    @if($transactions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Proyecto</th>
                                        <th>
                                            <a href="{{ route('transactions.index', array_merge(request()->except(['sort', 'dir']), ['sort' => 'date', 'dir' => ($sortBy === 'date' && $sortDir === 'desc') ? 'asc' : 'desc'])) }}"
                                               class="text-decoration-none text-dark d-flex align-items-center gap-1">
                                                Fecha
                                                @if($sortBy === 'date')
                                                    <i class="bi bi-arrow-{{ $sortDir === 'desc' ? 'down' : 'up' }}"></i>
                                                @else
                                                    <i class="bi bi-arrow-down-up text-muted"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th>Descripción</th>
                                        <th>
                                            <a href="{{ route('transactions.index', array_merge(request()->except(['sort', 'dir']), ['sort' => 'amount', 'dir' => ($sortBy === 'amount' && $sortDir === 'desc') ? 'asc' : 'desc'])) }}"
                                               class="text-decoration-none text-dark d-flex align-items-center gap-1">
                                                Monto
                                                @if($sortBy === 'amount')
                                                    <i class="bi bi-arrow-{{ $sortDir === 'desc' ? 'down' : 'up' }}"></i>
                                                @else
                                                    <i class="bi bi-arrow-down-up text-muted"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th>Tipo</th>
                                        <th width="120">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($transactions as $transaction)
                                        <tr>
                                            <td>
                                                @if($transaction->project)
                                                    <span class="badge bg-info">
                                                        {{ $transaction->project->name }}
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary">
                                                        Sin proyecto
                                                    </span>
                                                @endif
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
                                                <div class="d-flex gap-1" role="group">
                                                    <a href="{{ route('transactions.edit', $transaction->id) }}"
                                                       class="btn btn-sm btn-warning"
                                                       title="Editar">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <form id="delete-transaction-{{ $transaction->id }}"
                                                          action="{{ route('transactions.destroy', $transaction->id) }}"
                                                          method="POST"
                                                          style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button"
                                                                class="btn btn-sm btn-danger"
                                                                title="Eliminar"
                                                                onclick="confirmDelete('delete-transaction-{{ $transaction->id }}', '¿Eliminar transacción?', 'Esta acción no se puede deshacer.')">
                                                            <i class="bi bi-trash"></i>
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
                            <h5 class="text-muted mt-3">
                                No hay transacciones
                                @if(request('project') || request('month') || request('year') || request('search'))
                                    con los filtros seleccionados
                                @endif
                            </h5>
                            @if(request('project') || request('month') || request('year') || request('search'))
                                <a href="{{ route('transactions.index') }}" class="btn btn-secondary mt-2">
                                    <i class="bi bi-arrow-counterclockwise"></i> Ver todas las transacciones
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            {{-- Resumen de las transacciones --}}
            <div class="row g-3 mb-4 mt-4">
                <div class="col-md-4">
                    <div class="card text-white bg-success h-100">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="bi bi-arrow-up-circle"></i>
                                Total Ingresos
                            </h5>
                            <h2>L{{ number_format($totalIngresos, 2)}}</h2>
                            @if(request('month') && request('year'))
                                <small>
                                    {{ \Carbon\Carbon::create(request('year'), request('month'))->locale('es')->translatedFormat('F Y') }}
                                </small>
                            @elseif(request('year'))
                                <small>Año {{ request('year') }}</small>
                            @elseif(request('month'))
                                <small>
                                    {{ \Carbon\Carbon::create(null, request('month'))->locale('es')->translatedFormat('F') }}
                                </small>
                            @endif
                            @if(request('project') && $projects->find(request('project')))
                                <small class="d-block">Proyecto: {{ $projects->find(request('project'))->name }}</small>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-danger h-100">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="bi bi-arrow-down-circle"></i>
                                Total Egresos
                            </h5>
                            <h2>L{{ number_format($totalEgresos, 2)}}</h2>
                            @if(request('month') && request('year'))
                                <small>
                                    {{ \Carbon\Carbon::create(request('year'), request('month'))->locale('es')->translatedFormat('F Y') }}
                                </small>
                            @elseif(request('year'))
                                <small>Año {{ request('year') }}</small>
                            @elseif(request('month'))
                                <small>
                                    {{ \Carbon\Carbon::create(null, request('month'))->locale('es')->translatedFormat('F') }}
                                </small>
                            @endif
                            @if(request('project') && $projects->find(request('project')))
                                <small class="d-block">Proyecto: {{ $projects->find(request('project'))->name }}</small>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white {{ $balance >= 0 ? 'bg-primary' : 'bg-danger' }} h-100">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="bi bi-wallet2"></i>
                                Balance
                            </h5>
                            <h2>L{{ number_format($balance, 2)}}</h2>
                            @if(request('month') && request('year'))
                                <small>
                                    {{ \Carbon\Carbon::create(request('year'), request('month'))->locale('es')->translatedFormat('F Y') }}
                                </small>
                            @elseif(request('year'))
                                <small>Año {{ request('year') }}</small>
                            @elseif(request('month'))
                                <small>
                                    {{ \Carbon\Carbon::create(null, request('month'))->locale('es')->translatedFormat('F') }}
                                </small>
                            @endif
                            @if(request('project') && $projects->find(request('project')))
                                <small class="d-block">Proyecto: {{ $projects->find(request('project'))->name }}</small>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Gráficas --}}
            <div class="row mb-4 mt-4">
                {{-- Gráfica de Barras --}}
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="bi bi-bar-chart"></i> Gráfica de Barras
                            </h5>
                        </div>
                        <div class="card-body d-flex align-items-center justify-content-center">
                            <div style="position: relative; height: 400px; width: 100%;">
                                <canvas id="transactionChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Gráfica Circular --}}
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="bi bi-pie-chart"></i> Distribución de Transacciones
                            </h5>
                        </div>
                        <div class="card-body d-flex align-items-center justify-content-center">
                            <div style="position: relative; height: 400px; width: 100%;">
                                <canvas id="pieChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Cargar charts js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Determinar color del balance según sea positivo o negativo
    const balance = {{ $balance }};
    const balanceColor = balance >= 0 ? 'rgba(13, 110, 253, 0.7)' : 'rgba(220, 53, 69, 0.7)';
    const balanceBorder = balance >= 0 ? 'rgba(13, 110, 253, 1)' : 'rgba(220, 53, 69, 1)';

    // Gráfica de Barras
    const ctx = document.getElementById('transactionChart').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Ingresos', 'Egresos', 'Balance'],
            datasets: [{
                label: 'Monto (L)',
                data: [
                    {{ $totalIngresos }},
                    {{ $totalEgresos }},
                    balance
                ],
                backgroundColor: [
                    'rgba(40, 167, 69, 0.7)',
                    'rgba(220, 53, 69, 0.7)',
                    balanceColor
                ],
                borderColor: [
                    'rgba(40, 167, 69, 1)',
                    'rgba(220, 53, 69, 1)',
                    balanceBorder
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: false
                }
            },
            plugins: {
                legend: {
                    position: 'bottom',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let value = context.parsed.y;
                            return 'L' + value.toLocaleString('es-HN', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                        }
                    }
                }
            }
        }
    });

    // Gráfica Circular (Pie Chart)
    const ctxPie = document.getElementById('pieChart').getContext('2d');
    const pieChart = new Chart(ctxPie, {
        type: 'pie',
        data: {
            labels: ['Ingresos', 'Egresos'],
            datasets: [{
                label: 'Distribución',
                data: [
                    {{ $totalIngresos }},
                    {{ $totalEgresos }}
                ],
                backgroundColor: [
                    'rgba(40, 167, 69, 0.8)',   // Verde para ingresos
                    'rgba(220, 53, 69, 0.8)'    // Rojo para egresos
                ],
                borderColor: [
                    'rgba(40, 167, 69, 1)',
                    'rgba(220, 53, 69, 1)'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.label || '';
                            if (label) {
                                label += ': ';
                            }
                            label += 'L' + context.parsed.toLocaleString('es-HN', {minimumFractionDigits: 2, maximumFractionDigits: 2});

                            // Calcular porcentaje
                            const total = {{ $totalIngresos + $totalEgresos }};
                            const percentage = total > 0 ? ((context.parsed / total) * 100).toFixed(1) : 0;
                            label += ' (' + percentage + '%)';

                            return label;
                        }
                    }
                }
            }
        }
    });
</script>
@endsection
