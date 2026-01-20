<?php

namespace App\Http\Controllers;

use App\Exports\TransactionsExport;
use App\Http\Requests\TransactionRequest;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $projects = $user->projects;

        // Validar que el proyecto pertenece al usuario
        if ($request->filled('project')) {
            if (!$user->projects()->where('id', $request->project)->exists()) {
                return redirect()->route('transactions.index')
                    ->with('error', 'El proyecto seleccionado no es válido.');
            }
        }

        // Obtener los filtros
        $filters = [
            'project' => $request->integer('project') ?: null,
            'month' => $request->integer('month') ?: null,
            'year' => $request->integer('year') ?: null,
            'search' => $request->string('search')->trim()->toString() ?: null,
        ];

        // Obtener parámetros de ordenamiento
        $sortBy = $request->input('sort', 'date');
        $sortDir = $request->input('dir', 'desc');

        // Validar columnas permitidas para ordenar
        $allowedSorts = ['date', 'amount'];
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'date';
        }
        $sortDir = $sortDir === 'asc' ? 'asc' : 'desc';

        // Obtener transacciones paginadas con filtros
        $transactions = $user->transactions()
            ->with('project')
            ->applyFilters($filters['project'], $filters['month'], $filters['year'], $filters['search'])
            ->orderBy($sortBy, $sortDir)
            ->paginate(15)
            ->appends(array_merge($filters, ['sort' => $sortBy, 'dir' => $sortDir]));

        // Calcular resumen con los mismos filtros
        $summary = $user->transactions()
            ->applyFilters($filters['project'], $filters['month'], $filters['year'], $filters['search'])
            ->selectRaw("
                COALESCE(SUM(CASE WHEN type = 'ingreso' THEN amount END), 0) AS total_ingresos,
                COALESCE(SUM(CASE WHEN type = 'egreso' THEN amount END), 0) AS total_egresos
            ")->first();

        $totalIngresos = $summary->total_ingresos;
        $totalEgresos = $summary->total_egresos;
        $balance = $totalIngresos - $totalEgresos;

        // Obtener años disponibles desde la transacción más antigua hasta el actual
        $oldestYear = $user->transactions()->min('date');
        $oldestYear = $oldestYear ? (int) date('Y', strtotime($oldestYear)) : now()->year;
        $years = range(now()->year, $oldestYear);

        return view('transactions.index', compact('transactions', 'totalIngresos', 'totalEgresos', 'balance', 'projects', 'years', 'sortBy', 'sortDir'));
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
        // Verificar que el proyecto pertenece al usuario (doble validación de seguridad)
        $project = auth()->user()->projects()->find($request->project_id);
        if (!$project) {
            return redirect()->route('transactions.create')
                ->with('error', 'El proyecto seleccionado no es válido.')
                ->withInput();
        }

        auth()->user()->transactions()->create($request->validated());

        return redirect()->route('transactions.index')->with('success', 'Transacción creada exitosamente.');
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

    /**
     * Exportar transacciones a Excel
     */
    public function export(Request $request)
    {
        $user = auth()->user();

        // Validar que el proyecto pertenece al usuario
        if ($request->filled('project')) {
            if (!$user->projects()->where('id', $request->project)->exists()) {
                return redirect()->route('transactions.index')
                    ->with('error', 'El proyecto seleccionado no es válido.');
            }
        }

        // Obtener los filtros
        $filters = [
            'project' => $request->integer('project') ?: null,
            'month' => $request->integer('month') ?: null,
            'year' => $request->integer('year') ?: null,
            'search' => $request->string('search')->trim()->toString() ?: null,
        ];

        // Obtener transacciones con filtros
        $transactions = $user->transactions()
            ->with('project')
            ->applyFilters($filters['project'], $filters['month'], $filters['year'], $filters['search'])
            ->orderBy('date', 'desc')
            ->get();

        // Validar que haya transacciones para exportar
        if ($transactions->isEmpty()) {
            return redirect()->route('transactions.index', $filters)
                ->with('error', 'No hay transacciones para exportar con los filtros seleccionados.');
        }

        // Generar nombre del archivo dinámico
        $filename = $this->generateExportFilename($user, $filters);

        return Excel::download(new TransactionsExport($transactions), $filename);
    }

    /**
     * Genera el nombre del archivo de exportación
     */
    private function generateExportFilename($user, array $filters): string
    {
        $filename = 'transacciones';

        if ($filters['month'] && $filters['year']) {
            $filename .= "_{$filters['month']}_{$filters['year']}";
        } elseif ($filters['year']) {
            $filename .= "_{$filters['year']}";
        } elseif ($filters['month']) {
            $filename .= "_mes_{$filters['month']}";
        }

        if ($filters['project']) {
            $project = $user->projects()->find($filters['project']);
            if ($project) {
                $filename .= '_' . str_replace(' ', '_', $project->name);
            }
        }

        return $filename . '.xlsx';
    }
}
