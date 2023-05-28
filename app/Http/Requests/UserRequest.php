<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;


class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = Auth::user() ?: User::getUserAuthById(request()->user_id);
        return $user ? true : false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => [
                'sometimes',
                'required',
                'string', 
                'email', 
                'max:255',
                'unique:users'
            ], 
            'password' => [
                'sometimes', 
                'required', 
                'confirmed', 
                Rules\Password::defaults()
            ],
        ];
    }
}
