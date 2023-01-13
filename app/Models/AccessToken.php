<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class AccessToken extends Model
{
    // access_tokenを取得
    public static function get($userkey)
    {
        $access_token = self::refreshToken($userkey); // -v 失効していなければDBから取得
        $access_token ??= DB::table('users')->where('userkey', $userkey)->value('youtube_access_token');
        return $access_token;
    }


    // access_tokenが失効していたら更新する
    private static function refreshToken($userkey)
    {
        // -> access_tokenを最後に更新した日付を取得
        $updated_atstr = DB::table('users')->where('userkey', $userkey)->value('updated_at');
        $updated_at = strtotime($updated_atstr);

        // -> access_tokenの有効期限は30分
        $diff = round(abs(time() - $updated_at)/60);
        if($diff > 29) {

            // -> configからclient_idとclient_secretを取得
            $client_id = config('services.youtube.client_id');
            $client_secret = config('services.youtube.client_secret');
            $refresh_token = DB::table('users')->where('userkey', $userkey)->value('youtube_refresh_token');

            $url = "https://accounts.google.com/o/oauth2/token?&&&";
            $option = [
                'form_params' => [
                    'client_id' => $client_id,
                    'client_secret' => $client_secret,
                    'refresh_token' => $refresh_token,
                    'grant_type' => 'refresh_token'
                ]
            ];

            // -> API呼び出し
            $result = Model::APICilent($url, 'POST', $option);
            $access_token = $result['access_token'];

            // -> DBを更新してreturnする
            DB::table('users')->where('userkey', $userkey)->update([
                'youtube_access_token' => $access_token,
                'updated_at' => new \DateTime()
            ]);
            return $access_token;
        }
    }
}
