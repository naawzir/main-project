@extends('layouts.app')
@section('content')

	<div class="col-sm-20 window">
    <div class="row">
		<div class="row">
			<div class="col-sm-24">
				@include('layouts.breadcrumb')
		    	<h1>Home</h1>
			</div>
		 </div>
    </div>
	    <div id="content-area">

		<div class="row">
			<ul class="nav nav-tabs">
				<li class="nav-item">
					<a data-toggle="CaseDetails" class="nav-link active" href="#">Case Details</a>
				</li>
				<li class="nav-item">
					<a data-toggle="CaseNotes" class="nav-link" href="#">Case Notes</a>
				</li>
			</ul>
			<!-- Full width panel -->
			<section id="CaseDetails" class="clean-panel col-sm-23">
				@include('cases.partials._form')
			</section>

			<section id="CaseNotes" class="Panel col-sm-23 hidden">
				<table class="table table-bordered" id="branchTasksTable">
					<thead>
						<tr>
							<th id="due">Due</th>
							<th id="branch">Branch</th>
							<th id="lastcontact">Last Contact</th>
							<th id="view"></th>
						</tr>
					</thead>
				</table>
			</section>
		</div>
    </div>

@endsection
