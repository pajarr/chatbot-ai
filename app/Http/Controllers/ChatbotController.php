<?php

namespace App\Http\Controllers;
// app/Http/Controllers/ChatbotController.php
namespace App\Http\Controllers;

use App\Services\DialogflowRestService;
use App\Models\ChatConversation;
use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    protected $dialogflow;

    public function __construct(DialogflowRestService $dialogflow)
    {
        $this->dialogflow = $dialogflow;
    }

    public function handleMessage(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:500',
            'session_id' => 'required|string|max:100'
        ]);

        $response = $this->dialogflow->detectIntent(
            $validated['session_id'],
            $validated['message']
        );

        if ($response['success']) {
            ChatConversation::create([
                'session_id' => $validated['session_id'],
                'message' => $validated['message'],
                'response' => $response['response'],
                'intent' => $response['intent'],
                'confidence' => $response['confidence'],
                'parameters' => json_encode($response['parameters'])
            ]);
        }

        return response()->json($response);
    }

    public function showChat()
    {
        return view('chat', [
            'sessionId' => 'session_'.uniqid()
        ]);
    }
}