
// MODULES
var cookieModule = require('./cookie');


// MEMBERS
var user_key;


// MAIN
$(function() {
    // blockedwordsの読み込み
    user_key = cookieModule.get('user_key'); // -> userkeyの取得
});
