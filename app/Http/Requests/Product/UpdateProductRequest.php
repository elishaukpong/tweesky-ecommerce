<?php

namespace App\Http\Requests\Product;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('update', request()->route('product'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'sometimes|string',
            'description' => 'sometimes|string',
            'stock_quantity' => 'sometimes|numeric',
            'price' => 'sometimes|numeric',
        ];
    }

    protected function failedAuthorization()
    {
        throw new AuthorizationException(__('Unauthorized! You can only update a product created by you!'));
    }
}
