<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function update(UserRequest $request, $id)
    {
        $user = User::find($id);
        $fields = $request->validated();
        $user->update($fields);

        return response()->json([
            'message' => "Все хорошо"
        ], Response::HTTP_OK);
    }

    public function read($id)
    {
        
    }

    public function magicAvatar($id)
    {
        
    }
}
