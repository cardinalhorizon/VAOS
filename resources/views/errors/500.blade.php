<!DOCTYPE html>
<html>
<head>
    <title>500</title>

    <link href="https://fonts.googleapis.com/css?family=Cabin" rel="stylesheet" type="text/css">

    <style>
        html, body {
            height: 100%;
        }

        body {
            margin: 0;
            padding: 0;
            width: 100%;
            color: white;
            display: table;
            font-weight: 100;
            font-family: 'Cabin', sans-serif;
            background: url({{ asset('/img/vaos_df_bg-01.svg') }}) center no-repeat;
            background-size: cover;
        }

        .container {
            text-align: center;
            display: table-cell;
            vertical-align: middle;
        }

        .content {
            text-align: center;
            display: inline-block;
        }

        .title {
            font-size: 72px;
            margin-bottom: 40px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="content">
        <img src="{{asset('/img/MainLogo.svg')}}" style="min-width: 300px; width: 30vw;">
        <div class="title">That's not supposed to happen.</div>
        @if(Auth::user()->admin)
            <div>This is a generic 500 error. check your <span style="font-family: monospace; background: #666; border-radius: 3px; padding: 3px;">storage/logs/laravel.log</span> file for details about the error.</div>
        @endif
    </div>
</div>
</body>
</html>
