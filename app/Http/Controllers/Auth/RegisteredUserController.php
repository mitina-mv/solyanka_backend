<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\User;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\Response as Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        try {
            $user = User::create([
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'picture' => Chat::PATH_ICON . rand(1, 12) . '.png'
            ]);

            $chat = Chat::create([
                'name' => 'Ваш самый первый чат',
                'icon' => Chat::PATH_ICON . rand(1, 12) . ".png",
                'user_id' => $user->id
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => "Не удалось создать пользователя"
            ], HttpFoundationResponse::HTTP_BAD_REQUEST);
        }

        event(new Registered($user));

        Auth::login($user);
        session()->save();
        return response()->json([
            'user' => Auth::user(),
         ], Response::HTTP_OK);
    }
}
