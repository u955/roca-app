<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" type="text/css" href="css/normalize.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    @vite([
        'resources/js/_console.js',
        'resources/sass/console.scss'
    ])

    <title>ROCA - console</title>
</head>

<body class="root">
    <header class="header">
        <img class="logo" src="https://roca-s3.s3.amazonaws.com/app/trim-roca-logo02.png">

        <img class="avatar" src="{{ $avatar }}">
        <p>{{ $nickname }}としてサインインしています</p>
        <a href="{{ route('signout') }}" class="signout">サインアウト</a>
    </header>

    <hr>

    <h2 class="title">コンソール</h2>

    <div class="main">
        <iframe class="livechat widget border"
            src={{ route("livechat", "user_key=$user_key") }}>
        </iframe>

        <iframe class="eval widget border"
            src={{ route("eval", "user_key=$user_key") }}>
        </iframe>


        <div class="settings">
            <div>
                <p class="title flex">
                    <span class="icon material-symbols-outlined">link</span>
                    ライブチャットURL
                </p>
                <p>
                    下記のURLはYouTubeのライブチャットへのリダイレクトURLです。
                    このURLをOBSのブラウザソースに設定していただくと、自動で現在配信中のYouTubeライブチャットへとリダイレクトします。
                </p>

                <p>リダイレクトURL:</p>
                <div class="flex">
                    <input type="button" value="content_copy" class="icon material-symbols-outlined"onclick="copy('live_chat_url')">
                    <p id="live_chat_url">{{ route("livechat", "user_key=$user_key") }}</p>
                </div>
            </div>

            <hr>

            <div>
                <p class="title flex">
                    <span class="icon material-symbols-outlined">vpn_key</span>
                    ユーザーキー
                </p>
                <p>
                    user_keyはURLに使用される大切な情報です。他の人に教えないでください。
                    もし第三者に漏洩した場合は、速やかに下の「再発行」ボタンから再発行してください。
                </p>

                <p>あなたのuser_key:</p>
                <div class="flex">
                    <input type="button" value="content_copy" class="icon material-symbols-outlined" onclick="copy('user_key')">
                    <p id="user_key">{{ $user_key }}</p>
                </div>
                <a href="{{ route('reissue') }}">
                    <button id="user_key">user_keyを再発行します</button>
                </a>
            </div>

            <hr>

        </div>
    </div>
</body>
</html>
