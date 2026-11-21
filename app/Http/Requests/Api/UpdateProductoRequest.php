<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rulePrefix = $this->isMethod('patch') ? 'sometimes' : 'required';
        $producto = $this->route('producto');
        $minStock = $this->input('stock_minimo', $producto->stock_minimo ?? 0);

        return [
            'nombre' => [$rulePrefix, 'string', 'max:255'],
            'marca' => [$rulePrefix, 'string', 'max:255'],
            'stock' => [$rulePrefix, 'integer', 'gt:0'],
            'precio' => [$rulePrefix, 'numeric', 'gt:0'],
            'stock_minimo' => [$rulePrefix, 'integer', 'min:0'],
            'stock_maximo' => [$rulePrefix, 'integer', 'gt:' . $minStock],
            'imagen_url' => ['nullable', 'string', 'max:255'],
        ];
    }
}