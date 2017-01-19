<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="CoreUI Bootstrap 4 Admin Template">
    <meta name="author" content="Lukasz Holeczek">
    <meta name="keyword" content="CoreUI Bootstrap 4 Admin Template">
    <!-- <link rel="shortcut icon" href="assets/ico/favicon.png"> -->

    <title>CoreUI Bootstrap 4 Admin Template</title>

    <!-- Icons -->
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/simple-line-icons.css" rel="stylesheet">

    <!-- Main styles for this application -->
    <link href="css/style.css" rel="stylesheet">

</head>

<body>
<div class="container">
    <div class="row">
        <div class="col-md-5 m-x-auto pull-xs-none vamiddle">
            <div class="card">
                <div class="card-block p-a-2">
                    <h1>Register</h1>
                    <p class="text-muted">Create your account</p>
                    <form role="form" method="POST" action="{{ url('/register') }}">
                        {{ csrf_field() }}
                    <div class="input-group m-b-1">
                            <span class="input-group-addon"><i class="icon-user"></i>
                            </span>
                        <input type="text" name="first_name" class="form-control" placeholder="First Name" autofocus required>
                    </div>
                    <div class="input-group m-b-1">
                            <span class="input-group-addon"><i class="icon-user"></i>
                            </span>
                        <input type="text" name="last_name" class="form-control" placeholder="Last Name" required>
                    </div>
                    <div class="input-group m-b-1">
                            <span class="input-group-addon"><i class="icon-user"></i>
                            </span>
                        <input type="text" name="username" class="form-control" placeholder="Username" required>
                    </div>

                    <div class="input-group m-b-1">
                        <span class="input-group-addon">@</span>
                        <input type="text" class="form-control" name="email" placeholder="Email" required>
                    </div>

                    <div class="input-group m-b-1">
                            <span class="input-group-addon"><i class="icon-lock"></i>
                            </span>
                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                    </div>

                    <div class="input-group m-b-2">
                            <span class="input-group-addon"><i class="icon-lock"></i>
                            </span>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat password" required>
                    </div>

                    <button type="submit" class="btn btn-block btn-success">Create Account</button>
                    </form>
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
                <div class="panel-heading">Register</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/register') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Name</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

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

                        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                            <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>

                                @if ($errors->has('password_confirmation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Register
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

