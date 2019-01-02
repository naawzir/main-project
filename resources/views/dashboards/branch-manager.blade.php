@extends('dashboards._dashboard')

@section('heading')
	<h1>Home</h1>
@endsection

@section('dashboard')
	<div class="row">
		<div class="panel col-sm-23">
			<h4>Welcome <strong>{{$user->forenames}}</strong>, You are logged in as a Branch Manager!</h4>
		</div>
	</div>
@endsection
