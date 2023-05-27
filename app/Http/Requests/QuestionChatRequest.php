<?php

namespace App\Http\Requests;

use App\Models\Chat;
use App\Models\Role;
use Illuminate\Contracts\Session\Session;
use Illuminate\Validation\Rule as ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class QuestionChatRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if(Auth::user())
        {
            return true;
        }

        if($count = request()->get('limit'))
        {
            if($count == 0)
                return false;
            else return true;
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'question' => [                
                'required',
                'string', 'max:255'
            ],
            'chat_id' => [
                'sometimes',
                'required',
                'integer',
                ValidationRule::in(Chat::select('id')->pluck('id'))
            ],
            'role_id' => [
                'sometimes',
                'required',
                'integer',
                ValidationRule::in(Role::select('id')->pluck('id'))
            ],
            'limit' => [
                'sometimes',
                'required',
                'integer',
                'max:10'
            ],
        ];
    }
}
