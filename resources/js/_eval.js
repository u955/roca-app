
// MODULES
import * as cookieModule from './cookie';


// MEMBERS
var user_key;
var livechat_id;


// MAIN
$(function() {
    user_key = window.location.search.slice(1).replace('user_key=', ''); // -> URLクエリの取得
    getLiveBroadcast();
});


// ----- streaming ----- //

// livechatidの取得(LiveChat.php)
function getLiveBroadcast() {
    $.ajax({
        url: `/api/getlivebroadcast?userkey=${user_key}`,
        dataType: 'json'
    })
    .then(
        data => {
            livechat_id = data.livechatid;
            monitoring(); // -> streaming開始
        },
        error => {
            setFaildMessage();
    });
}


// livechatの取得(LiveChat.php)
const monitoring = function() {
    const pageToken = cookieModule.get('pageToken'); // -> cookieを参照

    $.ajax({
        // -> pageTokenがNullの場合''を返す
        url: `/api/monitoring?userkey=${user_key}&livechatid=${livechat_id}&pageToken=${pageToken ?? ''}`,
        dataType: 'json'
    })
    .then(
        data => {
            setLiveChatMessage(data); // -> ログの表示
            document.cookie = `pageToken=${data.nextPageToken}; max-age=30` // -> cookieを登録
            setTimeout(monitoring, 3000); // -> 同期間隔の設定
        },
        error => {
            setTimeout(monitoring, 3000); // -> 同期間隔の設定
        }
    );
}


// ----- display ----- //

// livechatMessageの反映
function setLiveChatMessage(data) {
    data.items.forEach(element => {
        const profileImageUrl = element.authorDetails.profileImageUrl;
        const isChatSponsor = (element.authorDetails.isChatSponsor == 1)? 'moderator' : '';
        const displayName = element.authorDetails.displayName;
        const displayMessage = element.snippet.displayMessage;
        const judgement = element.judgement;

        var html =`
        <img class='avatar' src='${profileImageUrl}'>

        <p class='displaymessage'>
            <span class='displayname ${isChatSponsor}'>${displayName}: ${judgement}</span>
            ${displayMessage}
        </p>
        `;

        html = `<div class='chat'>${html}</div>`;
        $('#log').append(html);
    });

    // -> liveChatの最下部まで移動
    let target = document.getElementById('log');
    target.scrollIntoView(false);
}


// getLiveBroadcastが失敗した時の処理
function setFaildMessage() {

    var html = `
    <p class='failed'>
        配信が開始されると、ここにログが表示されます。<br>
        <a href="${location.href}">再読み込み</a>
    </p>
    `;

    $('#log').append(html);
}
