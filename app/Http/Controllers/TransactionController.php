<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionRequest;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    // Asegura que el usuario este autenticado
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
    
        $transactions = $user->transactions()->orderBy('date', 'desc')->paginate(15);

        $summary = $user->transactions()->selectRaw("
            COALESCE(SUM(CASE WHEN type = 'ingreso' THEN amount END), 0) AS total_ingresos,
            COALESCE(SUM(CASE WHEN type = 'egreso' THEN amount END), 0) AS total_egresos
        ")->first();

        $totalIngresos = $summary->total_ingresos;
        $totalEgresos = $summary->total_egresos;
        $balance = $totalIngresos - $totalEgresos;

        return view('transactions.index', compact('transactions', 'totalIngresos', 'totalEgresos', 'balance'));

    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // form para una nueva transacción
        return view('transactions.create');
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

        return view('transactions.edit', compact('transaction'));
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
