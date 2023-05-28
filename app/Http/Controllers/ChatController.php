<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChatCreateRequest;
use App\Models\Chat;
use App\Models\Request as ModelsRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    // возвращает список чатов пользователя
    public function list()
    {   
        $user = Auth::user() ?: User::getUserAuthById(request()->user_id);
        if(!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Вы не авторизованы!'
            ], HttpFoundationResponse::HTTP_BAD_REQUEST);
        }

        $chats = Chat::where('user_id', $user->id)
                ->select('id', 'name', 'icon')
                ->get()->all();

        return response()->json($chats, HttpFoundationResponse::HTTP_OK);
    }

    // чат пользователя по id
    public function create(ChatCreateRequest $request)
    {
        $user = Auth::user() ?: User::getUserAuthById(request()->user_id);
        if(!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Вы не авторизованы!'
            ], HttpFoundationResponse::HTTP_BAD_REQUEST);
        }

        $chat = Chat::create([
            'name' => $request->name,
            'icon' => Chat::PATH_ICON . $request->icon . '.png',
            'user_id' => Auth::user()->id
        ]);

        return response()->json($chat, HttpFoundationResponse::HTTP_OK);
    }

    public function read($id)
    {
        $chat = $this->getChat($id);

        if(isset($chat['status']))
        {
            return response()->json($chat, HttpFoundationResponse::HTTP_BAD_REQUEST);
        } 

        // получаем все сообщения чата по его id
        $requests = ModelsRequest::where([
            'chat_id' => $chat->id
        ])->select('question', 'answer')->orderBy('created_at', 'asc')->get();

        return response()->json([
            'chat' => $chat,
            'history' => $requests
        ], HttpFoundationResponse::HTTP_OK);
    }

    public function destroy($id)
    {
        $chat = $this->getChat($id);

        if(isset($chat['status']))
        {
            return response()->json($chat, HttpFoundationResponse::HTTP_BAD_REQUEST);
        }

        foreach($chat->history() as $r)
        {
            $r->delete();
        }

        try {
            $chat->forceDelete();
        } catch (Exception $e) {
            $chat->delete();
        }

        return response()->json([
            'id' => $id,
            'message' => 'Удаление успешно'
        ], HttpFoundationResponse::HTTP_OK);
    }

    private function getChat($id)
    {
        $user = Auth::user() ?: User::getUserAuthById(request()->user_id);
        
        if($user)
        {
            $chat = Chat::where([
                'user_id' => $user->id,
                'id' => $id
            ])->first();
    
            if(!$chat)
            {
                return [
                    'status' => 'error',
                    'message' => "Чата с указанным ID не существует"
                ]; 
            }

            return $chat;
        } else {
            return [
                'status' => 'error',
                'message' => "Пользователь не авторизован, мы пока не знаем, что с этим делать"
            ]; 
        }

    }

    public function changeRole(Request $request, $id)
    {
        $chat = $this->getChat($id);

        if(isset($chat['status']))
        {
            return response()->json($chat, HttpFoundationResponse::HTTP_BAD_REQUEST);
        }

        $chat->update([
            'role_id' => $request->role_id
        ]);

        return response()->json([
            'id' => $id,
            'message' => 'Роль установлена'
        ], HttpFoundationResponse::HTTP_OK);
    }
}
