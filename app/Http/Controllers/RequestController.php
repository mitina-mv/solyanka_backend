<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuestionChatRequest;
use App\Models\Request as ModelsRequest;
use App\Models\Role;
use Exception;
use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;
use Symfony\Component\HttpFoundation\Response;

class RequestController extends Controller
{
    public function create(QuestionChatRequest $request)
    {
        $roleText = '';
        if($request->role_id)
        {
            $roleText = Role::find($request->role_id)->text . ". ";
        }

        // запрос к open ai 
        try {
            $result = OpenAI::completions()->create([
                'model' => 'text-davinci-003',
                'prompt' => $roleText . $request->question,
                'max_tokens' => 1500,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error', 
                'message' => 'Не удалось отправить запрос на сервис!'
            ], Response::HTTP_BAD_REQUEST);
        }

        if($request->chat_id)
        {
            $chatRecord = ModelsRequest::create([
                'chat_id' => $request->chat_id,
                'question' => $request->question,
                'role_id' => $request->role_id, 
                'answer' => trim($result['choices'][0]['text'])
            ]);

            return response()->json($chatRecord, Response::HTTP_OK);
        } else {
            return response()->json([
                'answer' => $result['choices'][0]['text'],
                'question' => $request->question,
                'limit' => $request->get('limit') - 1
            ], Response::HTTP_OK);
        }
    }
}
