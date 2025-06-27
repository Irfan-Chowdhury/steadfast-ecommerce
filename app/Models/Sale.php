<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sale extends Model
{
    protected $fillable = [
        'invoice_number',
        'date',
        'total_amount',
        'discount',
        'vat_percentage',
        'vat_amount',
        'paid_amount',
        'due_amount',
    ];

    protected $casts = [
        'date' => 'date',
        'total_amount' => 'decimal:2',
        'discount' => 'decimal:2',
        'vat_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'due_amount' => 'decimal:2',
    ];

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function journals(): HasMany
    {
        return $this->hasMany(Journal::class);
    }
}
