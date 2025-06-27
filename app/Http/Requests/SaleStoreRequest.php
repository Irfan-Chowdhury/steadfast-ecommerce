<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaleStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
            return [
                'sale_date'     => 'required|date|before_or_equal:today', // Changed from 'date'
                'products'      => 'required|array|min:1',
                'products.*.product_id' => 'required|exists:products,id',
                'products.*.quantity'   => 'required|integer|min:1',
                'products.*.unit_price' => 'required|numeric|min:0|decimal:0,2',
                'discount'      => 'nullable|numeric|min:0|decimal:0,2|lt:total_amount',
                'vat_percent'   => 'nullable|numeric|min:0|max:100|decimal:0,2', // Changed from 'vat_percentage'
                'vat_amount'    => 'nullable|numeric|min:0|decimal:0,2',
                'total_amount'  => 'nullable|numeric|min:0|decimal:0,2',
                'paid_amount'   => 'required|numeric|min:0|decimal:0,2|lte:total_amount',
                'due_amount'    => 'nullable|numeric|min:0|decimal:0,2',
            ];

    }

    public function messages()
    {
        return [
            'sale_date.required' => 'Sale date is required',
            'products.required' => 'At least one product is required',
            'products.*.product_id.exists' => 'Selected product is invalid',
            'discount.lt' => 'Discount cannot exceed total amount',
            'paid_amount.lte' => 'Paid amount cannot exceed total amount',
        ];
    }
}
