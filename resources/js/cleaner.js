
// 平坦化したblockedwordsを取得
var loadBlockedWords = (userkey) => blockedwords = blockedwordsModule.getFlat(userkey);
exports.loadBlockedWords = loadBlockedWords;


// livechatMessageをblockedwordsで変換
var convert = function(userkey, element) {
    var chatmessage = element.snippet.displayMessage; // -> chatmessageを取出し
    for(var key in blockedwords) {
        if(chatmessage.match(key)) { // -> blockedwordに部分一致するchatmessageを検知
            chatmessage = chatmessage.replace(key, blockedwords[key]);
            deleteLiveChatMessage(userkey, element.id); // -> youtube側のlivechatMessageを削除
    }}
    return chatmessage;
}
exports.convert = convert;


// livechatmessageの削除(LiveChat.php)
function deleteLiveChatMessage(userkey, id) {
    $.ajax({ // -> livechat messageを削除
        url: `/api/deletelivechatmessage?userkey=${userkey}&id=${id}`,
        dataType: 'json'
    });
}
