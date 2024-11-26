<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
            "code" => "size:10",
            "cost_price" => 'numeric',
            "selling_price" => 'numeric',
            "minimum_stock" => 'numeric|min:1',
            "expiration_date" => 'date',
            "category_id" => 'numeric',
            "supplier_id" => 'numeric'
        ];
    }
}
