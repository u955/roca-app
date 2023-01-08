<?php

namespace App\Http\Controllers;

use App\Models\Identifier;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;

class App extends Controller
{
    // ACCESS FROM URL
    public function index()
    {
        if(!Auth::check()) return redirect()->route('index'); // -> 未認証をindexへ

        // DB処理軽減のためcookieを利用する
        if(!Cookie::has('userid') || !Cookie::has('userkey')) {
            $userid  = Auth::user()->userid; // -> useridからuserkeyを取得
            $userkey = Auth::user()->userkey;

            Cookie::queue(Cookie::make('userkey', $userkey, 28800, null, null, null, false)); // -> cookieへ20日間保存
            Cookie::queue(Cookie::make('userid', $userid, 28800, null, null, null, false));
        }
        $userkey ??= Cookie::get('userkey'); // -> cookieからuserkeyを取得
        $nickname  = Auth::user()->nickname;
        $avatar    = Auth::user()->avatar;

        return view('app', compact('userkey', 'nickname', 'avatar')); // -> userkeyをlivechatのbladeへ渡す
    }


    // SIGN OUT
    public function signout()
    {
        if(Auth::check()) {
            Cookie::queue(Cookie::forget('userid'));
            Cookie::queue(Cookie::forget('userkey'));
            Auth::logout();
        }
        return redirect()->route('index'); // -> indexへ
    }


    // userkeyを再発行する
    public function reissueUserkey()
    {
        if(!Auth::check()) return redirect()->route('index'); // -> 未認証をindexへ

        $regacyUserkey = Auth::user()->userkey; // -v userkeyを生成
        $newUserkey = Identifier::generateToken('users', 'userkey');

        DB::table('users')->where('userkey', $regacyUserkey)->update([
            'userkey' => $newUserkey
        ]);

        Cookie::queue(Cookie::forget('userkey'));
        return redirect()->route('app'); // -> appへ
    }
}
