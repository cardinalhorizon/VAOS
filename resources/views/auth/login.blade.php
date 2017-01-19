<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="{{ config('app.name', 'VAOS') }} | Login to your airline">
    <meta name="author" content="Lukasz Holeczek">
    <meta name="keyword" content="CoreUI Bootstrap 4 Admin Template">
    <!-- <link rel="shortcut icon" href="assets/ico/favicon.png"> -->

    <title>Virtual Airline Operations System Login</title>

    <!-- Icons -->
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/simple-line-icons.css" rel="stylesheet">

    <!-- Main styles for this application -->
    <link href="css/style.css" rel="stylesheet">

</head>
<body style="background-image: url('../img/login.png'); background-repeat: no-repeat; background-size: 100%;">
<div class="container">
    <div class="row">
        <div class="col-md-8 m-x-auto pull-xs-none vamiddle">
            <div class="card-group ">
                <div class="card p-a-2">
                    <div class="card-block">
                        <h1>Login</h1>
                        <p class="text-muted">Sign In to your account</p>
                        <form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">
                            {{ csrf_field() }}
                            <div class="input-group m-b-1">
                                    <span class="input-group-addon"><i class="icon-user"></i>
                                    </span>
                                <input type="text" id="email" class="form-control" name="email" placeholder="Email">
                            </div>
                            @if ($errors->has('email'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                            @endif
                            <div class="input-group m-b-2">
                                    <span class="input-group-addon"><i class="icon-lock"></i>
                                    </span>
                                <input type="password" id="password" class="form-control" name="password" placeholder="Password">
                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="row">
                                <div class="col-xs-6">
                                    <button type="submit" class="btn btn-primary p-x-2">Login</button>
                                </div>
                                <div class="col-xs-6 text-xs-right">
                                    <button type="button" class="btn btn-link p-x-0">Forgot password?</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card card-inverse card-primary p-y-3 hidden-md-down" style="width:44%">
                    <div class="card-block text-xs-center">
                        <div>
                            <h2>Join {{ config('app.name', 'VAOS') }} Today</h2>
                            <p></p>
                            <a href="{{ url('/register') }}" role="button" class="button btn btn-primary">Register Now!</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap and necessary plugins -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<script src="bower_components/tether/dist/js/tether.min.js"></script>
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

<script>
    function verticalAlignMiddle() {
        var bodyHeight = $(window).height();
        var formHeight = $('.vamiddle').height();
        var marginTop = (bodyHeight / 2) - (formHeight / 2);

        if (marginTop > 0) {
            $('.vamiddle').css('margin-top', marginTop);
        }
    }

    $(document).ready(function(){
        verticalAlignMiddle();
    });
    $(window).bind('resize', verticalAlignMiddle);
</script>


</body>

</html>
<!--
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Login</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">Password</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember"> Remember Me
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Login
                                </button>

                                <a class="btn btn-link" href="{{ url('/password/reset') }}">
                                    Forgot Your Password?
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
