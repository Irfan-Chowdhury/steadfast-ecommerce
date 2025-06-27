<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $productId = $this->route('product')->id;

        return [
            'name'            => 'required|string|max:255|unique:products,name,' . $productId,
            'purchase_price'  => 'required|numeric|min:0',
            'sell_price'      => 'required|numeric|min:0|gte:purchase_price',
            'opening_stock'   => 'required|integer|min:0',
            'current_stock'   => 'required|integer|min:0||lte:opening_stock',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'           => 'Product name is required.',
            'name.unique'             => 'This product name already exists.',
            'purchase_price.required' => 'Purchase price is required.',
            'sell_price.gte'          => 'Sell price must be greater than or equal to purchase price.',
            'current_stock.lte'       => 'Current stock must be less than or equal to opening stock.',
        ];
    }
}
