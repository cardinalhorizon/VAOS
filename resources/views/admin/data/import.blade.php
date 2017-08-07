@extends('layouts.admin')

@section('breadcrumb')
    <li class="breadcrumb-item">Home</li>
    <li class="breadcrumb-item"><a href="{{ url('admin') }}">Admin</a>
    </li>
    <li class="breadcrumb-item"><a href="{{ url('admin/'.$route) }}">{{ ucfirst($route) }}</a>
    </li>
    <li class="breadcrumb-item active">Import &amp; Export</li>
@endsection

@section('content')
	<div class="row">
		<div class="col-lg-4">
			<div class="card">
				<div class="card-header">File Import</div>
				<div class="card-block">
					<p>Upload a .JSON file to be imported into the {{ $route }} table.</p>
					<form class="form-horizontal" role="form" method="POST" enctype="multipart/form-data" action="{{ url('/admin/data/'.$route.'?action=import') }}">
				        {{ csrf_field() }}
				        <input type="file" name="file" id="file">
				        <button type="submit" class="btn btn-success pull-right" role="button">Import</button>
				    </form>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12">
			<div class="card">
				<div class="card-header">File Format Example</div>
				<div class="card-block">
					<p>The file must be uploaded as a JSON (.json) format in the following structure. Below is an example of the structure that should be used.</p>
					@include('admin.partials.import_format.'.$route)
				</div>
			</div>
		</div>
	</div>
    
@endsection
