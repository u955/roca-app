<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', fn() => view('index'))->name('index');
Route::get('livechat',  'LiveChat@index')->name('livechat');

// App
Route::get('app', 'App@index')->name('app');
Route::get('signout', 'App@signout')->name('signout');
Route::get('reissue', 'App@reissueUserkey')->name('reissue');

// Auth
Route::get('auth/redirect', [OAuth::class, 'redirect'])->name('auth');
Route::get('auth/callback', [OAuth::class, 'callback']); // @ YOUTUBE: callback
