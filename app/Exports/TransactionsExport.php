<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TransactionsExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $transactions;

    public function __construct($transactions)
    {
        $this->transactions = $transactions;
    }

    /**
     * Retorna la colección de transacciones
     */
    public function collection()
    {
        return $this->transactions;
    }

    /**
     * Define los encabezados de las columnas
     */
    public function headings(): array
    {
        return [
            'Fecha',
            'Proyecto',
            'Descripción',
            'Tipo',
            'Monto (L)',
        ];
    }

    /**
     * Mapea cada transacción a las columnas del Excel
     */
    public function map($transaction): array
    {
        return [
            $transaction->date->format('d/m/Y'),
            $transaction->project?->name ?? 'Sin proyecto',
            $transaction->description,
            $transaction->type === 'ingreso' ? 'Ingreso' : 'Egreso',
            number_format($transaction->amount, 2),
        ];
    }

    /**
     * Aplica estilos a la hoja de Excel
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Estilo para la fila de encabezados
            1 => [
                'font' => ['bold' => true, 'size' => 12],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E2E8F0']
                ],
            ],
        ];
    }
}
