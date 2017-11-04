@extends('layouts.app')

@section('content')
<div class="container col-md-8">
    <div class="card">
        <div class="card-header">
            Login
        </div>
        <div class="card-body col-md-12 align-self-center">
            <form method="POST" action="{{ route('login') }}">
                {{ csrf_field() }}

                <div class="form-group row">
                    <label for="email" class="col-md-4 col-form-label">E-Mail Address</label>
                    <div class="col-md-8">
                        <input type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" id="email" value="{{ old('email') }}" required autofocus>

                        @if ($errors->has('email'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif

                    </div>
                </div>

                <div class="form-group row">
                    <label for="password" class="col-md-4 col-form-label">Password</label>
                    <div class="col-md-8">
                        <input type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" id="password" name="password" required>


                        @if ($errors->has('password'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-4"></div>
                    <div class="col-md-8">
                        <div class="form-check">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>Remember Me
                                </label>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-4"></div>
                    <div class="col-md-8">
                        <button type="submit" class="btn btn-primary">Login</button>
                        <a class="btn btn-link" href="{{ route('password.request') }}">
                            Forgot Your Password?
                        </a>
                    </div>
                </div>

            </form>
        </div>
    </div></div>
@endsection
