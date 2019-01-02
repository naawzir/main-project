@extends('layouts.app')
@section('content')

	<div class="col-sm-20 window">
    	@include('layouts.breadcrumb', [
    		"breadcrumbs" =>
    			[
    				"solicitors",
    				"panel"
    			]
    		]
    	)

    	<h1>Solicitor Panel</h1>
    </div>

@endsection
