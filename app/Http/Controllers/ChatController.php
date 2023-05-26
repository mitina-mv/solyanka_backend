<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;
use Illuminate\Support\Facades\Auth;



class ChatController extends Controller
{
   
    public function list()
    {   
        $user = Auth::user();
        // dd($user);
        $chats = Chat::where('user_id', $user -> id) -> get();

        return response()->json([
            'chats' => $chats,
        ], HttpFoundationResponse::HTTP_OK);
    }

}
