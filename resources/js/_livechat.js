
// MODULES
import * as cookieModule from './cookie';
import * as cleanerModule from './cleaner';


// MEMBERS
var user_key;
var livechat_id;


// MAIN
$(function() {
    user_key = window.location.search.slice(1).replace('user_key=', ''); // -> URLクエリの取得
    getLiveChatID();
});


// ----- streaming ----- //

// livechatidの取得(LiveChat.php)
function getLiveChatID() {
    $.ajax({
        url: `/api/getlivechatid?userkey=${user_key}`,
        dataType: 'json'
    })
    .then(
        data => {
            livechat_id = data;
            streaming(); // -> streaming開始
        },
        error => {
            setFaildMessage();
    });
}


// livechatの取得(LiveChat.php)
var streaming = function() {
    const pageToken = cookieModule.get('pageToken'); // -> cookieを参照

    $.ajax({
        // -> pageTokenがNullの場合''を返す
        url: `/api/streaming?userkey=${user_key}&livechatid=${livechat_id}&pageToken=${pageToken ?? ''}`,
        dataType: 'json'
    })
    .then(
        data => {
            setLiveChatMessage(data);
            document.cookie = `pageToken=${data.nextPageToken}; max-age=30` // -> cookieを登録
            setTimeout('streaming()', 3000); // -> 同期間隔の設定
        },
        error => {
            setTimeout('streaming()', 3000); // -> 同期間隔の設定
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
        const displayMessage = cleanerModule.convert(user_key, element);

        var html =`
        <img class='avatar' src='${profileImageUrl}'>

        <p class='displaymessage'>
            <span class='displayname ${isChatSponsor}'>${displayName}</span>
            ${displayMessage}
        </p>
        `;

        html = `<div class='chat'>${html}</div>`;
        $('#livechat').append(html);
    });

    // -> liveChatの最下部まで移動
    let target = document.getElementById('livechat');
    target.scrollIntoView(false);
}


// getLiveChatIDが失敗した時の処理
function setFaildMessage() {

    var html = `
    <p class='failed'>
        配信が開始されると、ここにチャットが表示されます。<br>
        <a href="${location.href}">再読み込み</a>
    </p>
    `;

    $('#livechat').append(html);
}
