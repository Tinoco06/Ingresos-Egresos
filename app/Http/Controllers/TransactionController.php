<?php

namespace App\Http\Controllers;

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
        // mostrar las transacciones del usuario
        $transactions = auth()->user()->transactions()->orderBy('date', 'desc')->get();

        // calcular totales del usuario
        $totalIngresos = $transactions->where('type', 'ingreso')->sum('amount');
        $totalEgresos = $transactions->where('type', 'egreso')->sum('amount');
        $balance = $totalIngresos - $totalEgresos;

        // retornar la vista con los datos
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
    public function store(Request $request)
    {
        // validar y guardar la nueva transaccion
        $validado = $request->validate([
            'type' => 'required|in:ingreso,egreso',
            'description' => 'required|string|max:255|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s]*$/',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
        ]);

        // asociar la transacción al usuario autenticado
        auth()->user()->transactions()->create($validado);

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
        // verificar que la transaccion sea del usuario autenticado
        if($transaction->user_id !== auth()->id()){
            abort(403);
        }

        return view('transactions.edit', compact('transaction'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $transaction)
    {
        // verificar que la transaccion sea del usuario autenticado
        if($transaction->user_id !== auth()->id()){
            abort(403);
        }

        $validado = $request->validate([
            'type' => 'required|in:ingreso,egreso',
            'description' => 'required|string|max:255|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s]*$/',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
        ]);

        // actualizar la transacción 
        $transaction->update($validado);

        // retroalimentación al usuario
        return redirect()->route('transactions.index')->with('success','Transacción actualizada correctamente.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        // verificar que la transaccion sea del usuario autenticado
        if($transaction->user_id !== auth()->id()){
            abort(403);
        }

        // eliminar la transacción
        $transaction->delete();

        return redirect()->route('transactions.index')->with('success','Transacción eliminada correctamente.');
    }

    // función para la generación de grafica 
    public function Data(){
        
        // obtener las transacciones del usuario
        $transactions = auth()->user()->transactions;

        $ingresos = $transactions->where('type','ingreso')->sum('amount');
        $egresos = $transactions->where('type','egreso')->sum('amount');

        return response()->json([
            'ingresos' => $ingresos,
            'egresos' => $egresos,
            'balance' => $ingresos - $egresos
        ]);

    }
}
