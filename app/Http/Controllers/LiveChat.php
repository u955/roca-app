<?php

namespace App\Http\Controllers;

use App\Models\AccessToken;
use App\Models\APICilent;
use Illuminate\Http\Request;

class LiveChat extends Controller
{
    private $key;

    function __construct()
    {
        // -> config/servicesからyoutube_api_keyを取得
        $this->key = config('services.youtube.api_key');
    }

    public function index(Request $request)
    {
        // -> URLクエリからuser_keyを取得
        $user_key = $request->user_key;
        if($request->path() == 'livechat') {
            return view('livechat' , compact('user_key'));
        }else {
            return view('eval' , compact('user_key'));
        }
    }


    // ----- API ----- //

    // liveBroadcastを返すAPI
    public function getLiveBroadcast(Request $request)
    {
        // -> user_keyが存在しなければ処理しない
        if(AccessToken::userKeyDoesntExist($request->user_key)) return;

        $access_token = AccessToken::get($request->userkey); // -> user_keyからaccess_tokenを取得
        $url = "https://youtube.googleapis.com/youtube/v3/liveBroadcasts?part=snippet&broadcastStatus=active&maxResults=1&access_token={$access_token}&key={$this->key}";

        // -> API呼び出し
        $liveStreamingDetails = APICilent::APIgetJSON($url, 'GET');
        $liveChatID = $liveStreamingDetails['items'][0]['snippet']['liveChatId'];
        $videoID = $liveStreamingDetails['items'][0]['id'];

        return response()->json([
            'livechatid'=> $liveChatID,
            'videoid' => $videoID
        ]);
    }


    // liveChatを監視するAPI
    public function monitoring(Request $request)
    {
        // -> user_keyが存在しなければ処理しない
        if(AccessToken::userKeyDoesntExist($request->user_key)) return;

        $livechatid = $request->livechatid; // -> URLクエリからliveChatIdを取得
        $pageToken = $request->pageToken; // -> URLクエリからpageTokenを取得

        $livechat = $this->streaming($livechatid, $pageToken);
        $livechat['items'] = $this->evalLiveChatMessages($livechat);
        return response()->json($livechat);
    }


    // ----- Model: Evaluation ----- //

    // livechatの評価
    private function evalLiveChatMessages($data, $evaluated=[])
    {
        // dataからdisplayMessageのみを取り出し
        $texts = array_map(fn($item): string =>
            $item['snippet']['displayMessage'], $data['items']);

        // textsをAIに評価させる
        $results = $this->evalTexts($texts);

        // 評価を基に判定
        foreach($results['body'] as $i => $result) {
            $neutral = $result['score']['neutral'];

            // neutralとの差で判定する
            $calc = [
                'neutral'     => $neutral + $neutral,
                'slander'     => $neutral + $result['score']['slander'],
                'sarcasm'     => $neutral + $result['score']['sarcasm'],
                'sexual'      => $neutral + $result['score']['sexual'],
                'spam'        => $neutral + $result['score']['spam'],
                'divulgation' => $neutral + $result['score']['divulgation']
            ];
            // 差の値が最小の項目が判定値となる
            $judgement = array_search(max($calc), $calc);
            $result += Array('judgement' => $judgement);
            array_push($evaluated, $data['items'][$i] + $result);
        }
        return $evaluated;

        // ------------------------削除・対処処理
        // ------------------------ログに書き込み
    }


    // ----- Model: Roca API ----- //

    private function evalTexts($texts)
    {
        $url = "https://ivqjs20obe.execute-api.us-east-1.amazonaws.com/roca-api-apigateway-eval-stage";
        $option = ['json' => ['texts' => $texts]];

        // -> API呼び出し
        $results = APICilent::APIgetJSON($url, 'POST', $option);
        return $results;
    }


    // ----- Model: YouTube API ----- //

    // liveChatの取得
    private function streaming($id, $pageToken)
    {
        $url = "https://youtube.googleapis.com/youtube/v3/liveChat/messages?part=snippet%2CauthorDetails&liveChatId={$id}&key={$this->key}";
        if(!is_null($pageToken)) $url .= "&pageToken={$pageToken}"; // -> pageTokenがnullじゃないとき、pageTokenクエリを挿入

        // -> API呼び出し
        $liveChat = APICilent::APIgetJSON($url, 'GET');
        return $liveChat;
    }


    // livechatMessageの削除
    private function deleteLiveChatMessage($id, $user_key)
    {
        $access_token = AccessToken::get($user_key); // -> user_keyからaccess_tokenを取得
        $url = "https://youtube.googleapis.com/youtube/v3/liveChat/messages?id={$id}&access_token={$access_token}&key={$this->key}";

        // -> API呼び出し
        $result = APICilent::APIgetJSON($url, 'DELETE');
        return $result;
    }
}
