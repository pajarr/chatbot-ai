<?php

use App\Http\Controllers\ChatbotController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ChatbotController::class, 'showChat']);
Route::post('/chat/send', [ChatbotController::class, 'handleMessage']);
