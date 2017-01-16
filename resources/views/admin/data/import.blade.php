@extends('layouts.admin')
@section('content')
    <form class="form-horizontal" role="form" method="POST" enctype="multipart/form-data" action="{{ url('/admin/data/'.$route.'?action=import') }}">
        {{ csrf_field() }}
        <input type="file" name="file" id="file">
        <button type="submit" class="btn btn-success" role="button">Submit</button>
    </form>
@endsection