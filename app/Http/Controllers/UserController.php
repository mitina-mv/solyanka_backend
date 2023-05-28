<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function update(UserRequest $request, $id)
    {
        if(Auth::user()->id != $id){
            return response()->json([
                'status' => 'error',
                'message' => "Вы не имеете право править этот профиль"
            ], Response::HTTP_BAD_REQUEST);
        }

        $user = User::find($id);
        
        $fields = $request->validated();
        $user->update($fields);

        return response()->json([
            'message' => "Все хорошо"
        ], Response::HTTP_OK);
    }

    public function read($id)
    {
        if(Auth::user()->id != $id){
            return response()->json([
                'status' => 'error',
                'message' => "Вы не имеете право смотреть этот профиль"
            ], Response::HTTP_BAD_REQUEST);
        }  
    }

    public static function magicAvatar($id)
    {
        
    }
}
