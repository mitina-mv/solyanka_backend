<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChatCreateRequest;
use App\Models\Chat;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
   
    // возвращает список чатов пользователя
    public function list()
    {   
        $user = Auth::user();
        $chats = Chat::where('user_id', $user->id)->get();

        return response()->json([
            'chats' => $chats,
        ], HttpFoundationResponse::HTTP_OK);
    }

    // чат пользователя по id
    public function create(ChatCreateRequest $request)
    {
        $chat = Chat::create([
            'name' => $request->name,
            'icon' => $request->icon,
            'user_id' => Auth::user()->id
        ]);

        return response()->json([
            'chat' => $chat,
        ], HttpFoundationResponse::HTTP_OK);
    }
}
