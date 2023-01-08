<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Identifier;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class OAuth extends Controller
{
    public function redirect()
    {
        // -> 認証済をappへ
        if(Auth::check()) return redirect('app');

        $scopes = [
            "https://www.googleapis.com/auth/youtube.force-ssl",
            "https://www.googleapis.com/auth/youtube.readonly"
        ];

        $parameters = [
            'access_type' => 'offline', // refresh_tokenを取得するためのパラメタ
            "prompt" => "consent select_account" // アカウントを選択するプロンプトを表示するためのパラメタ
        ];

        // -> 認証開始
        return Socialite::driver('youtube')->
            scopes($scopes)->
            with($parameters)->
            redirect();
    }


    public function callback()
    {
        // -> 認証情報を取得
        $channel = Socialite::driver('youtube')->
            stateless()->
            user();

        \Log::debug($channel);// loggggggggggggggggggggggggggggggggggg

        // -> 新規認証時にDBに登録
        $user = User::firstOrCreate(['userid' => $channel->id], [

            // rocaのアカウント情報
            'refreshed_at' => new \DateTime(),
            'userkey' => Identifier::generateToken('users', 'userkey'),

            // youtubeのアカウント情報
            'refresh_token' => $channel->refreshToken,
            'access_token' => $channel->token,
            'userid' => $channel->id,
            'nickname' => $channel->nickname,
            'avatar' => $channel->avatar
        ]);
        Auth::login($user, true);

        // -> 新規認証をappへ
        if($user->wasRecentlyCreated) return redirect('app');

        // -> 再認証をappへ
        $user->refresh_token = $channel->refreshToken;
        $user->save(); // -> refresh_tokenを更新
        return redirect('app');
    }
}
