<?php

namespace App\Http\Controllers;

use App\Models\Identifier;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;

class Console extends Controller
{
    // ACCESS FROM URL
    public function index()
    {
        if(!Auth::check()) return redirect()->route('index'); // -> 未認証をindexへ

        // DB処理軽減のためcookieを利用する
        if(!Cookie::has('youtube_id') || !Cookie::has('user_key')) {
            $youtube_id  = Auth::user()->youtube_id;
            $user_key     = Auth::user()->user_key;

            Cookie::queue(Cookie::make('user_key', $user_key, 28800, null, null, null, false)); // -> cookieへ20日間保存
            Cookie::queue(Cookie::make('youtube_id', $youtube_id, 28800, null, null, null, false));
        }
        $user_key ??= Cookie::get('user_key'); // -> cookieからuser_keyを取得
        $nickname  = Auth::user()->youtube_nickname;
        $name      = Auth::user()->youtube_name;
        $email     = Auth::user()->youtube_email;
        $avatar    = Auth::user()->youtube_avatar;

        return view('console', compact('user_key', 'nickname', 'name', 'email', 'avatar')); // -> user_keyをlivechatのbladeへ渡す
    }


    // SIGN OUT
    public function signout()
    {
        if(Auth::check()) {
            Cookie::queue(Cookie::forget('youtube_id'));
            Cookie::queue(Cookie::forget('user_key'));
            Auth::logout();
        }
        return redirect()->route('index'); // -> indexへ
    }


    // user_keyを再発行する
    public function reissueUserKey()
    {
        if(!Auth::check()) return redirect()->route('index'); // -> 未認証をindexへ

        $regacy_user_key = Auth::user()->user_key; // -> user_keyを生成
        $new_user_key = Identifier::generateToken('users', 'user_key');

        DB::table('users')->where('user_key', $regacy_user_key)->update([
            'user_key' => $new_user_key
        ]);

        Cookie::queue(Cookie::forget('user_key'));
        return redirect()->route('console'); // -> consoleへ
    }
}
