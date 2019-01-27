<!DOCTYPE html>
<html>
    <head>
        <title>Maintenance Mode</title>

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
                <div class="title">We're Doing Database Maintenance!<br>Be back in a short bit.</div>
            </div>
        </div>
    </body>
</html>
