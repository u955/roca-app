
// cookieの取得
var get = function(key = '') {
    const cookies = document.cookie.split(';');
    for (let cookie of cookies) { // -> (key[0],value[1])
        var kv = cookie.split('=');
        if (kv[0].trim() == key) return kv[1];
    }
}
exports.get = get;
