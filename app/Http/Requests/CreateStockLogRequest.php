<?php

namespace App\Http\Requests;

use App\Enums\StockLogTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateStockLogRequest extends FormRequest
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
            "quantity" => "min:1|required",
            "product_id" => "numeric|required",
            "type" => ['required', Rule::enum(StockLogTypeEnum::class)]
        ];
    }
}
