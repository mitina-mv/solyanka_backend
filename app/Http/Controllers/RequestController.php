<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;


class RequestController extends Controller
{
    public function create(Request $request)
    {
        # question, role_id, chat_id

        $result = OpenAI::completions()->create([
            'model' => 'text-davinci-003',
            'prompt' => 'Скажи мне как гангстеру, как не погибнуть от пуль',
            'max_tokens' => 1000,
        ]);

        dd($result['choices'][0]['text']);
    }
}
