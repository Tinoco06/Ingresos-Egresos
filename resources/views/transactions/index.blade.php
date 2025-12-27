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
                        <h5 class="mb-0">Mis transacciones</h5>
                        <a href="{{ route('transactions.create')}}" class="btn btn-primary">Nueva Transacción</a>
                    </div>
                    <div class="card-body">
                        @if($transactions->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Descripción</th>
                                            <th>Monto</th>
                                            <th>Tipo</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($transactions as $transaction)
                                            <tr>
                                                <td>{{$transaction->date->format('Y-m-d')}}</td>
                                                <td>{{$transaction->description}}</td>
                                                <td>L{{ number_format($transaction->amount, 2) }}</td>
                                                <td>
                                                    @if($transaction->type == 'ingreso')
                                                        <span class="badge bg-success">Ingreso</span>
                                                    @else
                                                        <span class="badge bg-danger">Egreso</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('transactions.edit', $transaction->id) }}" class="btn btn-sm btn-warning">Editar</a>
                                                    <form action="{{ route('transactions.destroy', $transaction->id) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p>No hay transacciones.</p>
                        @endif
                    </div>
                </div>
            {{-- Resumen de las transacciones --}}
            <div class="row mb-4 mt-4">
                <div class="col-md-4">
                    <div class="card text-white bg-success">
                        <div class="card-body">
                            <h5 class="card-title">Total Ingresos</h5>
                            <h2>L{{ number_format($totalIngresos, 2)}}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-danger">
                        <div class="card-body">
                            <h5 class="card-title">Total Egresos</h5>
                            <h2>L{{ number_format($totalEgresos, 2)}}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-primary">
                        <div class="card-body">
                            <h5 class="card-title">Balance</h5>
                            <h2>L{{ number_format($balance, 2)}}</h2>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Gráfica --}}
            <div class="card mb-4 mt-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Gráfica de Ingresos vs Egresos</h5>
                </div>
                <div class="card-body">
                    <canvas id="transactionChart"></canvas>
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