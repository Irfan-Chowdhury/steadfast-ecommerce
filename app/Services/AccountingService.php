<?php

namespace App\Services;

use App\Models\Journal;
use App\Models\Sale;

class AccountingService
{
    public function recordSaleTransaction(Sale $sale): void
    {
        Journal::create([
            'sale_id' => $sale->id,
            'type' => 'Sales',
            'amount' => $sale->total_amount,
            'entry_type' => 'Credit',
        ]);

        if ($sale->discount > 0) {
            Journal::create([
                'sale_id' => $sale->id,
                'type' => 'Discount',
                'amount' => $sale->discount,
                'entry_type' => 'Debit',
            ]);
        }

        if ($sale->vat_amount > 0) {
            Journal::create([
                'sale_id' => $sale->id,
                'type' => 'VAT',
                'amount' => $sale->vat_amount,
                'entry_type' => 'Credit',
            ]);
        }

        if ($sale->paid_amount > 0) {
            Journal::create([
                'sale_id' => $sale->id,
                'type' => 'Payment',
                'amount' => $sale->paid_amount,
                'entry_type' => 'Debit',
            ]);
        }

        if ($sale->due_amount > 0) {
            Journal::create([
                'sale_id' => $sale->id,
                'type' => 'Payment',
                'amount' => $sale->due_amount,
                'entry_type' => 'Debit',
            ]);
        }
    }
}

