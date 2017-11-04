@extends('layouts.app')

@section('content')
	<div class="container">

		<div class="starter-template">

			<h1>HOME</h1>
			@if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

			<p class="lead">
			You are logged in
			</p>

		</div>

	</div><!-- /.container -->
@endsection
