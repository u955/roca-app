<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="css/normalize.css">

    @vite([
        'resources/sass/index.scss'
    ])

    <title>ROCA</title>
</head>

<body class="root">
    <div class="section top">
        <img class="logo" src="/storage/logo.png">

        <div class="desct">
            <h1>
                ・誹謗中傷を検知、自動で変換。<br>
                ・変換はリアルタイム。
            </h1>
            <p>
                YouTubeのライブチャットに書き込まれる<br>
                誹謗中傷を綺麗な言葉へ変換します。<br>
            </p>
            <p>
                OBSを用いて配信画面上に表示します。<br>
                設定は簡単です。
            </p>
        </div>

        <img class="desci" src="/storage/illust01.png">

        <a href="{{ route('auth') }}" class="signin">
            <input type="button" value="ログインまたは新規登録">
        </a>
    </div>

    <hr>
</body>
</html>
