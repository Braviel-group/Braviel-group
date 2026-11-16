<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminRecord extends Model
{
    protected $fillable = ['module', 'title', 'data', 'created_by'];

    protected function casts(): array
    {
        return ['data' => 'array'];
    }

    public function getLabelAttribute(): string
    {
        if (filled($this->title)) {
            return $this->title;
        }

        $fields = [
            'products' => ['sku'],
            'customers' => ['contact_name', 'email', 'phone'],
            'suppliers' => ['contact_name', 'email', 'phone'],
            'brands' => ['short_name'],
            'units' => ['abbreviation'],
            'categories' => ['description'],
            'inventory' => ['product', 'movement_type', 'movement_date'],
            'sales' => ['customer', 'sale_date', 'total_amount'],
            'purchases' => ['supplier', 'purchase_date', 'total_amount'],
            'purchase-returns' => ['supplier', 'return_date', 'total_amount'],
            'stock-transfers' => ['product', 'transfer_date'],
            'stock-adjustments' => ['product', 'reason', 'adjustment_date'],
            'expenses' => ['category', 'expense_date', 'amount'],
            'payments' => ['reference', 'payment_date', 'amount'],
            'returns' => ['sale_reference', 'return_date'],
            'cash-register' => ['status', 'opened_at'],
            'settings' => ['setting_key'],
            'expense-categories' => ['category_name'],
        ];

        foreach ($fields[$this->module] ?? [] as $field) {
            if (filled($this->data[$field] ?? null)) {
                return (string) $this->data[$field];
            }
        }

        return ucfirst(str_replace('-', ' ', $this->module)).' #'.$this->getKey();
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
