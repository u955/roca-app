<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\App;
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
Route::view('/', 'index')->name('index');
Route::get('livechat', [LiveChat::class ,'index'])->name('livechat');

// App
Route::get('app',     [App::class, 'app'])->name('app');
Route::get('signout', [App::class, 'signout'])->name('signout');
Route::get('reissue', [App::class, 'reissueUserkey'])->name('reissue');

// Auth
Route::get('auth/redirect', [OAuth::class, 'redirect'])->name('auth');
Route::get('auth/callback', [OAuth::class, 'callback']); // @ YOUTUBE: callback
