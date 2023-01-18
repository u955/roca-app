
// MEMBERS
var user_key;


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
            // -> livechat表示
            location.href = `https://www.youtube.com/live_chat?v=${data.videoid}&embed_domain=${location.hostname}`;
        },
        error => {
            setFaildMessage();
    });
}


// getLiveBroadcastが失敗した時の処理
function setFaildMessage() {

    var html = `
    <p class='failed'>
        配信が開始されると、ここにチャットが表示されます。<br>
        <a href="${location.href}">再読み込み</a>
    </p>
    `;

    $('#livechat').append(html);
}
