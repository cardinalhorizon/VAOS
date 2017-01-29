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
					<p>Upload a CSV file to be imported into the {{ $route }} table.</p>
					<form class="form-horizontal" role="form" method="POST" enctype="multipart/form-data" action="{{ url('/admin/data/'.$route.'?action=import') }}">
				        {{ csrf_field() }}
				        <input type="file" name="file" id="file">
				        <button type="submit" class="btn btn-success pull-right" role="button">Import</button>
				    </form>
				</div>
			</div>
		</div>
		<div class="col-lg-8">
			<div class="card">
				<div class="card-header">File Format Example</div>
				<div class="card-block">
					<p>The file must be uploaded as a CSV format in the following structure. Below is an example of the structure that should be used.</p>
					<table class="table table-border table-inverse">
						<tr>
							<td>airline</td>
							<td>icao</td>
							<td>name</td>
							<td>manufacturer</td>
							<td>registration</td>
							<td>range</td>
							<td>maxgw</td>
							<td>maxpax</td>
							<td>status</td>
						</tr>
						<tr>
							<td>1</td>
							<td>B738</td>
							<td>B737-800</td>
							<td>Boeing</td>
							<td>N351JX</td>
							<td>5500</td>
							<td>155000</td>
							<td>200</td>
							<td>1</td>
						</tr>
						<tr>
							<td>1</td>
							<td>A320</td>
							<td>A320-200</td>
							<td>Airbus</td>
							<td>N352JX</td>
							<td>5500</td>
							<td>155000</td>
							<td>200</td>
							<td>1</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
	</div>
    
@endsection