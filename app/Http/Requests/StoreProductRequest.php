<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "name" => 'required',
            "code" => "max:10|required",
            "cost_price" => 'numeric',
            "selling_price" => 'numeric',
            "minimum_stock" => 'numeric|min:1',
            "expiration_date" => 'date',
            "category_id" => 'numeric|required',
            "supplier_id" => 'numeric|required'
        ];
    }
}
