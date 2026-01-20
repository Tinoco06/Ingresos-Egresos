<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = auth()->user();

        // Calcular SOLO del mes actual
        $summaryMes = $user->transactions()
            ->whereMonth('date', now()->month)  // Filtra por mes actual (ejemplo: enero = 1)
            ->whereYear('date', now()->year)    // Filtra por año actual (ejemplo: 2026)
            ->selectRaw("
                COALESCE(SUM(CASE WHEN type = 'ingreso' THEN amount END), 0) AS ingresos_mes,
                COALESCE(SUM(CASE WHEN type = 'egreso' THEN amount END), 0) AS egresos_mes,
                COUNT(*) AS transacciones_mes
            ")->first();

        // Extraer los valores del resultado
        $ingresosMes = $summaryMes->ingresos_mes;
        $egresosMes = $summaryMes->egresos_mes;
        $transaccionesMes = $summaryMes->transacciones_mes;

        return view('home', compact('ingresosMes', 'egresosMes', 'transaccionesMes'));
    }
}
