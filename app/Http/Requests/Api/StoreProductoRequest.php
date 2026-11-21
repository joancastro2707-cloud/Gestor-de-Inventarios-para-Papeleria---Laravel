<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:255'],
            'marca' => ['required', 'string', 'max:255'],
            'stock' => ['required', 'integer', 'gt:0'],
            'precio' => ['required', 'numeric', 'gt:0'],
            'stock_minimo' => ['required', 'integer', 'min:0'],
            'stock_maximo' => ['required', 'integer', 'gt:stock_minimo'],
            'imagen_url' => ['nullable', 'string', 'max:255'],
        ];
    }
}