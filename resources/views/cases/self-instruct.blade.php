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
				<div class="panel-body">
                    <div class= "panel col-sm-24">
                        <h3><strong>Client Details</strong></h3>
						<div class="row">
							<div class="form group" style="padding-left:10px;">
								<label for="name"><strong>Title</strong></label>
								<input type="select" class="form-control" placeholder="Forename" value="">
							</div> 
							<div class="form group" style="padding-left:10px;">
								<label for="name"><strong>Forenames</strong></label>
								<input type="text" class="form-control" placeholder="Forename" value="">
							</div>
							<div class="form group" style="padding-left:10px;">
								<label for="name"><strong>Surname</strong></label>
								<input type="text" class="form-control" placeholder="Surname" value="">
							</div> 
							<div class="form group" style="padding-left:10px;">
								<label for="name"><strong>Email</strong></label>
								<input type="text" class="form-control" placeholder="Email" value="">
							</div> 
							<div class="form group" style="padding-left:10px;">
								<label for="name"><strong>Phone</strong></label>
								<input type="text" class="form-control" placeholder="Phone" value="">
							</div> 
							<div class="form group" style="padding-left:10px; padding-bottom:15px;">
								<label for="name"><strong>Date Of Birth</strong></label>
								<input type="date" class="form-control" placeholder="Email" value="">
							</div> 
						</div>
						<p><a href="">+ Add Another Client</a></p>
                    </div>
				</div>
                <div class="panel-body">
                    <div class= "panel col-sm-24">
                        <h3><strong>Client Address Details</strong></h3>
						<div class="row">
							<div class="form group" style="padding-left:10px;">
								<label for="name"><strong>Client</strong></label>
								<input type="text" class="form-control" placeholder="Client" value="">
							</div> 
							<div class="form group" style="padding-left:10px;">
								<label for="name"><strong>Postcode Search</strong></label>
								<input type="text" class="form-control" placeholder="Postcode" value="">
							</div> 
							<div class="form group" style="padding-left:10px; padding-bottom:15px;">
								<label for="name"><strong>Select Address</strong></label>
								<input type="text" class="form-control" placeholder="Select Address" value="">
							</div> 
						</div>
						<p><a href="">+ Add Another Clients Address</a></p>
                    </div>
				</div>                
                <div class="panel-body">
                    <div class= "panel col-sm-24">
                        <h3><strong>Transaction Details</strong></h3>
                    </div>
				</div>
                <div class="panel-body">
                    <div class= "panel col-sm-24">
                        <h3><strong>Addition Questions</strong></h3>
                    </div>
				</div>
                <div class="panel-body">
                    <div class= "panel col-sm-24">
                        <h3><strong>Documents</strong></h3>
                    </div>
				</div>
                <div class="panel-body">
                    <div class= "panel col-sm-24">
                        <h3><strong>Solicitor</strong></h3>
                    </div>
				</div>
			</section>
			<section id="CaseNotes" class="clean-panel col-sm-23 hidden">
				<div class="panel-body">
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
				</div>
			</section>
		
		</div>
    
    </div>

@endsection