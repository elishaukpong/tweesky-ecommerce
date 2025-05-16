<?php

namespace App\Http\Requests\Wishlist;

use App\Models\Wishlist;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

class ShowWishlistRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('view', request()->route('wishlist'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }

    protected function failedAuthorization()
    {
        throw new AuthorizationException(__('Unauthorized! You can only view a wishlist created by you!'));
    }
}
