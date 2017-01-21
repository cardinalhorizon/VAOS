@extends('layouts.crewops')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">My Profile</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Profile Settings</h3>
                </div>
                <form action="" method="post">
                    <div class="panel-body">
                        @if(count($errors))
                            <div class="alert alert-danger">
                                <strong>The following error(s) occurred:</strong>
                                <ul>
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="form-group">
                            <label>Email Address:</label>
                            <input type="text" name="email" value="{{ Auth::user()->email }}" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>VATSIM ID:</label>
                            <input type="text" name="vatsim" value="{{ Auth::user()->vatsim }}" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>IVAO ID:</label>
                            <input type="text" name="ivao" value="{{ Auth::user()->ivao }}" class="form-control">
                        </div>
                        <hr>
                        <div class="form-group">
                            <label>New Password:</label>
                            <input type="password" name="password" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Confirm Password:</label>
                            <input type="password" name="password2" class="form-control">
                        </div>
                    </div>
                    <div class="panel-footer">
                        <a href="{{ url('flightops/profile/' . Auth::id()) }}" class="btn btn-default">Cancel</a>
                        {{ csrf_field() }}
                        <button type="submit" class="btn btn-success pull-right">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection