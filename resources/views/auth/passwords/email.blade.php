@extends('layouts.app')

<!-- Main Content -->
@section('content')
<div class="center">
  <div class="row form z-depth-1">
        <h3 style="font-weight: 100">Reset Password</h3>
        @if (session('status'))
          <div class="card-panel green lighten-2">
            <p class="white-text text-darken-2">{{ $error }}</p>
          </div>
        @endif
        @if (count($errors) > 0)
          <ul class="card-panel red lighten-2">
              @foreach($errors->all() as $error)
                  <li class="white-text text-darken-2">{{ $error }}</li>
              @endforeach
          </ul>
        @endif
        <form role="form" method="POST" action="{{ url('/password/email') }}">
          {!! csrf_field() !!}
          <div class="row">
            <div class="input-field col s12">
              <input type="email" name="email" type="text">
              <label for="email">Email Address</label>
            </div>
          </div>
          <div class="row">
            <div class="input-field col s12 center">
                <button type="submit" class="btn">
                    <i class="fa fa-btn fa-sign-in"></i>Send Password Reset Link
                </button>
            </div>
          </div>
         </form>
     </div>
</div>
@endsection
