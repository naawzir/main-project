@extends('layouts.app')

@section('content')

	<div class="col-sm-20 window">
		<div class="row">
			@section('header')
			<div class="col-sm-24">
				@section('breadcrumbs')
					@include('layouts.breadcrumb')
				@show
				@yield('heading')
			</div>
			@show
		</div>

		<div id="content-area" style="margin-left: 0">
			@yield('dashboard')
		</div>
    </div>
@endsection
