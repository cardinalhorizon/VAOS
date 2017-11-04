@extends('layouts.app')

@section('content')
<div class="container col-md-8">
    <div class="card">
        <div class="card-header">
            Reset Password
        </div>
        <div class="card-body col-md-12 align-self-center">

            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif


            <form method="POST" action="{{ route('password.email') }}">
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
                    <div class="col-md-4"></div>
                    <div class="col-md-8">
                        <button type="submit" class="btn btn-primary">Send Password Reset Link</button>

                    </div>
                </div>

            </form>
        </div>
    </div></div>
@endsection
