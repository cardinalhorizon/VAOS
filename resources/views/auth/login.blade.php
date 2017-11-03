@extends('layouts.app')

@section('content')
<div class="center">
      <div class="row form z-depth-1">
        <h3 style="font-weight: 100">Login</h3>
        @if (count($errors) > 0)
          <ul class="card-panel red lighten-2">
              @foreach($errors->all() as $error)
                  <li class="white-text text-darken-2">{{ $error }}</li>
              @endforeach
          </ul>
        @endif
        <form role="form" method="POST" action="{{ url('/login') }}">
          <div class="row">
            <div class="input-field col s12">
              <input type="email" name="email">
              <label for="email">Email Address</label>
            </div>
          </div>
          <div class="row">
            <div class="input-field col s12">
            <input type="password" name="password">
            <label for="password">Password</label>
          </div>
          </div>
          <div class="row">
            <div class="input-field col s12">
            <input type="checkbox" name="remember" id="remember"/>
            <label for="remember">Remember me</label>
          </div>
          </div>
          <div class="row">
            <div class="input-field col s12 center">
                <button type="submit" class="btn">
                    <i class="fa fa-btn fa-sign-in"></i>Login
                </button>
            </div>
          </div>
          <div class="row">
            <div class="col s12 center">
                <a href="{{ url('/password/reset') }}">Forgot your Password?</a>
              </div>
          </div>
        </form>
      </div>
</div>
@endsection
