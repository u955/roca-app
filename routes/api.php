<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LiveChat;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// API
Route::get('streaming', [LiveChat::class, 'streaming']);
Route::get('getlivechatid', [LiveChat::class, '@getLiveChatID']);
Route::get('deletelivechatmessage', [LiveChat::class, 'deleteLiveChatMessage']);
