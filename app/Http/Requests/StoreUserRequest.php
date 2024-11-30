<?php

namespace App\Http\Requests;

use App\Enums\UserRoleEnum;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $usersCount = User::count();
        if ($usersCount === 0) return false;

        $user = auth('sanctum')->user();

        return (isset($user) && $user->role !== "common");
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'fullname' => 'required',
            "email" => 'required|email',
            "cellphone" => "required",
            "password" => "required",
            "role" => [Rule::enum(UserRoleEnum::class)]
        ];
    }
}
