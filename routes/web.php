<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\App;
use App\Http\Controllers\LiveChat;
use App\Http\Controllers\OAuth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Common
Route::redirect('/', 'https://zigzag-shovel-e41.notion.site/ROCA-AI-db53de64197f440d9cc9914ab2fcb228')->name('index');
Route::get('livechat', [LiveChat::class ,'index'])->name('livechat');
Route::get('eval', [LiveChat::class ,'index'])->name('eval');

// App
Route::get('app',     [App::class, 'index'])->name('app');
Route::get('signout', [App::class, 'signout'])->name('signout');
Route::get('reissue', [App::class, 'reissueUserkey'])->name('reissue');

// Auth
Route::get('auth/redirect', [OAuth::class, 'redirect'])->name('auth');
Route::get('auth/callback', [OAuth::class, 'callback']); // @ YOUTUBE: callback
