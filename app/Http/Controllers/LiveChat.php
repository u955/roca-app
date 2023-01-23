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
        if(AccessToken::userKeyDoesntExist($request->userkey)) return;

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
        if(AccessToken::userKeyDoesntExist($request->userkey)) return;

        $user_key = $request->userkey;
        $livechatid = $request->livechatid; // -> URLクエリからliveChatIdを取得
        $pageToken = $request->pageToken; // -> URLクエリからpageTokenを取得

        // liveChatの取得と判定
        $livechat = $this->streaming($livechatid, $pageToken);
        $livechat['items'] = $this->evalLiveChatMessages($livechat, $user_key);
        return response()->json($livechat);
    }


    // ----- Model: Evaluation ----- //

    // livechatの評価
    private function evalLiveChatMessages($data, $user_key, $results=[])
    {
        // dataからdisplayMessageのみを取り出し
        $texts = array_map(fn($item): string =>
            $item['snippet']['displayMessage'], $data['items']);

        // textsをAIに評価させる
        $evaluatedData = $this->evalTexts($texts);

        // 評価を基に判定
        foreach($evaluatedData['body'] as $i => $evaluated) {
            $neutral = $evaluated['score']['neutral'];

            // neutralとの差で判定する
            $calc = [
                'neutral'     => $neutral + $neutral,
                'slander'     => $neutral + $evaluated['score']['slander'],
                'sarcasm'     => $neutral + $evaluated['score']['sarcasm'],
                'sexual'      => $neutral + $evaluated['score']['sexual'],
                'spam'        => $neutral + $evaluated['score']['spam'],
                'divulgation' => $neutral + $evaluated['score']['divulgation']
            ];
            // 差の値が最小の項目が判定値となる
            $judgement = array_search(max($calc), $calc);
            $evaluated += Array('judgement' => $judgement);

            // 分析結果をresultsに格納
            array_push($results, $data['items'][$i] + Array('roca' => $evaluated));

            // 判定がneutral以外の時、対処処理
            if($judgement != 'neutral') {
                $id = $data['items'][$i]['id'];
                $this->deleteLiveChatMessage($id, $user_key);
            }
        }
        return $results;
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
