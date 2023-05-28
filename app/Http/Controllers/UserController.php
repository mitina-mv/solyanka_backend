<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function update(UserRequest $request, $id)
    {
        $user = Auth::user() ?: User::getUserAuthById(request()->user_id);
        if(!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Вы не авторизованы!'
            ], Response::HTTP_BAD_REQUEST);
        }

        if($user->id != $id){
            return response()->json([
                'status' => 'error',
                'message' => "Вы не имеете право править этот профиль"
            ], Response::HTTP_BAD_REQUEST);
        }
        
        $fields = $request->validated();
        $user->update($fields);

        return response()->json([
            'message' => "Все хорошо"
        ], Response::HTTP_OK);
    }

    public function read($id)
    {
        $user = Auth::user() ?: User::getUserAuthById(request()->user_id);
        if(!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Вы не авторизованы!'
            ], Response::HTTP_BAD_REQUEST);
        }

        if($user->id != $id){
            return response()->json([
                'status' => 'error',
                'message' => "Вы не имеете право смотреть этот профиль"
            ], Response::HTTP_BAD_REQUEST);
        }  

        return response()->json([
            'user' => $user,
            'message' => "Все хорошо"
        ], Response::HTTP_OK);
    }

    public static function magicAvatar($id)
    {
        $user = Auth::user() ?: User::getUserAuthById(request()->user_id);
        if(!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Вы не авторизованы!'
            ], Response::HTTP_BAD_REQUEST);
        }

        $user->update([
            'picture' => Chat::PATH_ICON . rand(1, 12) . '.png'
        ]);

        return response()->json([
            'picture_path' => $user->picture
        ], Response::HTTP_OK);
    }
}
