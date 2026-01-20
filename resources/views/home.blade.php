@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <h2 class="mb-4">Bienvenido(a) {{ Auth::user()->name }}</h2>

            <div class="row justify-content-center">
                {{-- Card 1: Transacciones --}}
                <div class="col-md-5 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="bi bi-cash-coin" style="font-size: 4rem; color: #6f42c1;"></i>
                            </div>
                            <h4 class="card-title">Transacciones</h4>
                            <p class="card-text text-muted">Administra tus ingresos y egresos</p>
                            <a href="{{ route('transactions.index') }}" class="btn btn-lg w-100" style="background-color: #6f42c1; color: white;">
                                Ver Transacciones
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Card 2: Mis Proyectos --}}
                <div class="col-md-5 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="bi bi-folder text-primary" style="font-size: 4rem;"></i>
                            </div>
                            <h4 class="card-title">Mis Proyectos</h4>
                            <p class="card-text text-muted">Organiza tus transacciones por proyecto</p>
                            <a href="{{ route('projects.index') }}" class="btn btn-primary btn-lg w-100">
                                Ver Proyectos
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Métricas del Mes Actual --}}
            <div class="row mt-5">
                <div class="col-12 mb-3">
                    <h4 class="text-muted">
                        <i class="bi bi-calendar-month me-2"></i>
                        Resumen del Mes - {{ now()->locale('es')->translatedFormat('F Y') }}
                    </h4>
                    <hr>
                </div>

                {{-- Card: Ingresos del Mes --}}
                <div class="col-md-4 mb-4">
                    <div class="card border-success shadow-sm h-100">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="bi bi-arrow-up-circle-fill text-success" style="font-size: 3rem;"></i>
                            </div>
                            <h5 class="card-title text-muted mb-3">Ingresos del Mes</h5>
                            <h2 class="text-success fw-bold">L{{ number_format($ingresosMes, 2) }}</h2>
                        </div>
                    </div>
                </div>

                {{-- Card: Egresos del Mes --}}
                <div class="col-md-4 mb-4">
                    <div class="card border-danger shadow-sm h-100">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="bi bi-arrow-down-circle-fill text-danger" style="font-size: 3rem;"></i>
                            </div>
                            <h5 class="card-title text-muted mb-3">Egresos del Mes</h5>
                            <h2 class="text-danger fw-bold">L{{ number_format($egresosMes, 2) }}</h2>
                        </div>
                    </div>
                </div>

                {{-- Card: Transacciones del Mes --}}
                <div class="col-md-4 mb-4">
                    <div class="card border-info shadow-sm h-100">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="bi bi-receipt text-info" style="font-size: 3rem;"></i>
                            </div>
                            <h5 class="card-title text-muted mb-3">Transacciones del Mes</h5>
                            <h2 class="text-info fw-bold">{{ $transaccionesMes }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
