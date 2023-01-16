
// MODULES
import * as cookieModule from './cookie';


// MEMBERS
var user_key;


// MAIN
$(function() {
    // blockedwordsの読み込み
    user_key = cookieModule.get('user_key'); // -> userkeyの取得
});
