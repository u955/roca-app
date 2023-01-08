<?php

namespace App\Http\Controllers;

use App\Models\AccessToken;
use App\Models\APICilent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LiveChat extends Controller
{
    private $key;

    function __construct()
    {
        // -> config/servicesからyoutube_api_keyを取得
        $this->key = config('services.youtube.api_key');
    }


    // ACCESS FROM URL
    public function index(Request $request)
    {
        // -> URLクエリからuserkeyを取得
        $userkey = $request->userkey;
        return view('livechat' , compact('userkey'));
    }


    // livechatidを返すAPI
    public function getLiveChatID(Request $request)
    {
        $access_token = AccessToken::get($request->userkey); // -> userkeyからaccess_tokenを取得
        $url = "https://youtube.googleapis.com/youtube/v3/liveBroadcasts?part=snippet&broadcastStatus=active&maxResults=1&access_token={$access_token}&key={$this->key}";

        // -> API呼び出し
        $liveStreamingDetails = APICilent::APIgetJSON($url, 'GET');
        $liveChatID = $liveStreamingDetails['items'][0]['snippet']['liveChatId'];
        return response()->json($liveChatID);
    }


    // liveChatを取得するAPI
    public function streaming(Request $request)
    {
        // -> userkeyが一致しなければ処理しない
        if(DB::table('users')->where('userkey', $request->userkey)->doesntExist()) return;

        $id = $request->livechatid; // -> URLクエリからliveChatIdを取得
        $pageToken = $request->pageToken; // -> URLクエリからpageTokenを取得

        $url = "https://youtube.googleapis.com/youtube/v3/liveChat/messages?part=snippet%2CauthorDetails&liveChatId={$id}&key={$this->key}";
        if(!is_null($pageToken)) $url .= "&pageToken={$pageToken}"; // -> pageTokenがnullじゃないとき、pageTokenクエリを挿入

        // -> API呼び出し
        $liveChat = APICilent::APIgetJSON($url, 'GET');
        return response()->json($liveChat);
    }


    // livechatMessageを削除するAPI
    public function deleteLiveChatMessage(Request $request)
    {
        $id = $request->id; // -> livechatmessageidを取得
        $access_token = AccessToken::get($request->userkey); // -> userkeyからaccess_tokenを取得
        $url = "https://youtube.googleapis.com/youtube/v3/liveChat/messages?id={$id}&access_token={$access_token}&key={$this->key}";

        // -> API呼び出し
        $result = APICilent::APIgetJSON($url, 'DELETE');
        return response()->json($result);
    }
}
