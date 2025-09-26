<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Product;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {
        return [
            'customer_name' => ['required', 'max:50'],
            'status' => ['required'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => [
                'required',
                'integer',
                'min:1',
                function ($attribute, $value, $fail) {
                    $index = explode('.', $attribute)[1] ?? null;
                    if ($index !== null && isset($this->items[$index]['product_id'])) {
                        $product = Product::find($this->items[$index]['product_id']);
                        if ($product && $value > $product->quantity) {
                            $fail("موجودی محصول «{$product->name}» کافی نیست (موجودی فعلی: {$product->quantity}).");
                        }
                    }
                }
            ],
        ];
    }
}
