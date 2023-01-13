<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" type="text/css" href="css/normalize.css">

    @vite([
        'resources/js/_app.js',
        'resources/sass/app.scss'
    ])

    <title>ROCA - App</title>
</head>

<body class="root">
    <header class="header">
        <img class="logo" src="/storage/logo.png">

        <img class="avatar" src="{{ $avatar }}">
        <p>{{ $nickname }}としてサインインしています</p>
        <a href="{{ route('signout') }}" class="signout">サインアウト</a>
    </header>

    <hr>

    <h2 class="title">コンソール</h2>

    <div class="main">
        <iframe class="livechat border"
            src={{ route("livechat", "user_key=$user_key") }}>
        </iframe>

        <div class="settings">
            <p>このURLをOBSのブラウザソースに設定してください</p>
            <input class="border url" type="text" value="{{ route("livechat", "user_key=$user_key") }}" >

            <hr>

            <p class="caution">
                user_keyはURLに使用される大切な情報です。他の人に教えないでください。
                万一漏洩してしまった場合は、下の「再発行」ボタンから再発行してください。
            </p>
            <p class="float">あなたのuser_key: </p>
            <input class="border key" type="text" value="{{ $user_key }}" >

            <hr>

            <a href="{{ route('reissue') }}">
                <button id="user_key">user_keyの再発行</button>
            </a>
        </div>
    </div>
</body>
</html>
