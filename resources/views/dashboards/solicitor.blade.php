@extends('dashboards._dashboard')

@section('heading')
	<h1>Home</h1>
@endsection

@section('dashboard')
	<div class="row">
		<section class="panel col-sm-23">
			<h4>Welcome <strong>{{$user->forenames}}</strong>, You are logged in as a Solicitor</h4>
		</section>
		<div class="panel col-sm-23" id="Current Activity">
		</div>
		<div class="panel col-sm-23" id="Offices">
		<div class="row">
					<section><h4><strong>Offices</strong></h4></section>
				</div>
				<div class="row">
					<table class="table table-bordered" id="Offices">
						<thead>
							<tr>
								<th>Solicitor</th>
								<th>Pipeline</th>
								<th>Average Completion (Days)</th>
								<th>Customer Rating</th>
								<th>Agent Rating</th>
								<th>Legal Fee</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
						@foreach ($dashData['mySolicitors'] as $mySolicitor)
						<tr>
							<td>{{$mySolicitor->Solicitor}}<br><strong>{{$mySolicitor->OfficeName}}</strong></td>
							<td>{{$mySolicitor->pipeline}}</td>
							<td>{{$mySolicitor->AverageCompletion}}</td>
							<td>{{$mySolicitor->CustomerRating}}</td>
							<td>{{$mySolicitor->AgentRating}}</td>
							<td>£{{$mySolicitor->legal_fee}}</td>
							<td><a href="/solicitors/office/{{$mySolicitor->slug}}/fees">
								<button class="success-button">View</button></td>
						</tr>
						@endforeach
						</tbody>
					</table>
				</div>
		</div>
	</div>
@endsection
