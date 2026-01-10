<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionRequest;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        // Obtener todos los proyectos para el filtro
        $projects = $user->projects;

        // Query base de transacciones
        $query = $user->transactions()->with('project');

        // Aplicar filtro de proyecto si existe
        if ($request->filled('project')) {
            $query->where('project_id', $request->project);
        }

        // Obtener transacciones paginadas
        $transactions = $query->orderBy('date', 'desc')->paginate(15)->appends(['project' => $request->project]);

        // Calcular resumen
        $summaryQuery = $user->transactions();
        if ($request->filled('project')) {
            $summaryQuery->where('project_id', $request->project);
        }

        $summary = $summaryQuery->selectRaw("
            COALESCE(SUM(CASE WHEN type = 'ingreso' THEN amount END), 0) AS total_ingresos,
            COALESCE(SUM(CASE WHEN type = 'egreso' THEN amount END), 0) AS total_egresos
        ")->first();

        $totalIngresos = $summary->total_ingresos;
        $totalEgresos = $summary->total_egresos;
        $balance = $totalIngresos - $totalEgresos;

        return view('transactions.index', compact('transactions', 'totalIngresos', 'totalEgresos', 'balance', 'projects'));

    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Obtener proyectos del usuario para el selector
        $projects = auth()->user()->projects;

        return view('transactions.create', compact('projects'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TransactionRequest $request)
    {
        auth()->user()->transactions()->create($request->validated());

        return redirect()->route('transactions.index')->with('success', 'Transacción creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        $this->authorize('update', $transaction);

        // Obtener proyectos del usuario para el selector
        $projects = auth()->user()->projects;

        return view('transactions.edit', compact('transaction', 'projects'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TransactionRequest $request, Transaction $transaction)
    {
        $this->authorize('update', $transaction);

        $transaction->update($request->validated());

        return redirect()->route('transactions.index')->with('success','Transacción actualizada correctamente.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        $this->authorize('delete', $transaction);

        // eliminar la transacción
        $transaction->delete();

        return redirect()->route('transactions.index')->with('success','Transacción eliminada correctamente.');
    }

    // función para la generación de grafica 
    public function data(){
        $summary = auth()->user()->transactions()->selectRaw("
            COALESCE(SUM(CASE WHEN type = 'ingreso' THEN amount END), 0) AS ingresos,
            COALESCE(SUM(CASE WHEN type = 'egreso' THEN amount END), 0) AS egresos
        ")->first();

        return response()->json([
            'ingresos' => $summary->ingresos,
            'egresos' => $summary->egresos,
            'balance' => $summary->ingresos - $summary->egresos,
        ]);
    }
}
