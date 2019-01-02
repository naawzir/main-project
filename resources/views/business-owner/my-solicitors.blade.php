@extends('layouts.app')
@section('content')
    <div class="col-sm-20 window">
	<div class="row">
		<div class="col-sm-24">
			@include('layouts.breadcrumb',
                ['breadcrumbs' =>
                    [
                     'my-solicitors' => 'My Solicitors',
                    ]
                ]
             )

			<div class="row" name="Title">
				<div class="col-sm-17">
					<h1><strong>My Solicitors</strong></h1>
				</div>
			</div>
			@if(!empty($mySolicitors))
            <div class="row" name="Performance">
				<div class="col-sm-1"></div>
				<div class="panel col-sm-22">
					<div class="row">
						<section><h4><strong>Performance</strong></h4>
						<p>MTD</p></section>
					</div>
					<div class="row">
                        <div class="col-sm-3 ">
							<label class="listing-label">Solicitor:</label>
							<select class="listing-select solicitor-performance-filter" id="SolicitorsFilter">
    						<option value="all">View All</option>
							@foreach($solicitors as $solicitor)
							<option value="{{ $solicitor->id }}">{{ $solicitor->name }}</option>
							@endforeach
							</select>
                        </div>
						<div class="col-sm-1"></div>
						<div class="col-sm-4">
							<div class="notification-circle instructions-kpi kpi" id="instructed">
								<p><span id="instructionsCount">{{ $statusCount->instructed }}</span>
								<br>Instructions
								</p>
							</div>
						</div>
						<div class="col-sm-1"></div>
						<div class="col-sm-4">
							<div class="notification-circle darker-grey kpi" id="instructed_unpanelled">
								<p>
								<span id="unpanelledInstructionsCount">{{ $statusCount->instructed_unpanelled }}</span>
								<br>Unpanelled Instructions
								</p>
							</div>
						</div>
						<div class="col-sm-1"></div>
						<div class="col-sm-4">
							<div class="notification-circle success kpi" id="completed">
								<p>
								<span id="completionsCount">{{ $statusCount->completed }}</span>
								<br>Completions
								</p>
							</div>
						</div>
						<div class="col-sm-1"></div>
						<div class="col-sm-4">
							<div class="notification-circle warning kpi" id="aborted">
								<p>
								<span id="abortedCount">{{ $statusCount->aborted }}</span>
								<br>Aborted
								</p>
							</div>
						</div>
					</div>
				</div>
			</div>
            <div class="row" name="My Panel">
                <div class="col-sm-1"></div>
				<div class="panel col-sm-22">
					<div class="row">
						<section><h4><strong>My Panel</strong></h4></section>
					</div>
					<div class="row">
                        <table class="table table-bordered" id="">
                            <thead>
                                <tr>
                                    <th>Solicitor</th>
                                    <th>Pipeline</th>
                                    <th>Average Completion (Days)</th>
                                    <th>Customer Rating</th>
                                    <th>Agent Rating</th>
                                    <th>Legal Fee</th>
                                    <th>(Action)</th>
                                </tr>
                            </thead>
							<tbody>
							@foreach ($mySolicitors as $mySolicitor)
							<tr>
								<td>{{$mySolicitor->Solicitor}}<br><strong>{{$mySolicitor->OfficeName}}</strong></td>
								<td>{{$mySolicitor->pipeline}}</td>
								<td>{{$mySolicitor->AverageCompletion}}</td>
								<td>{{$mySolicitor->CustomerRating}}</td>
								<td>{{$mySolicitor->AgentRating}}</td>
								<td>£{{$mySolicitor->legal_fee}}</td>
								<td>
									<a href="{{ route('panel.office', $mySolicitor->slug) }}" class="success-button">
										View
									</a>
								</td>
							</tr>
							@endforeach
							</tbody>
                        </table>
					</div>
				</div>
			</div>
            <div class="row" name="NoContact">
                <div class="col-sm-1"></div>
				<div class="panel col-sm-22">
					<div class="row">
						<section><h4><strong>No Contact In The Last 7 Days</strong></h4></section>
					</div>
					<div class="row">
                        <table class="table table-bordered" id="">
                            <thead>
                                <tr>
                                    <th>Solicitor</th>
                                    <th>Case Ref</th>
                                    <th>Agency Branch</th>
                                    <th>Staff</th>
                                    <th>Days Since Last Contract</th>
                                </tr>
                            </thead>
                        </table>
					</div>
				</div>
			</div>
			@else
				<div id="content-area">
					<div class="panel col-sm-23">
						<div class="panel-body">
							<h4 class="p-1"><strong>You do not have any Solicitors assigned to your panel</strong></h4>
						</div>
					</div>
				</div>
			@endif
        </div>
	</div>
</div>
@endsection

@push('scripts')

   <script type="text/javascript">

	   $(document).ready(function() {
			$("#SolicitorsFilter").change(function() {
				var solicitor_id = $(this).val();

				   const data = {
					solicitor_id : solicitor_id
					};

					const ajaxRequest = tcp.xhr.get('/solicitors/update-status-kpis', data);
					ajaxRequest.done(function(data) {
						$("#instructionsCount").text(data.count.instructed);
						$("#unpanelledInstructionsCount").text(data.count.instructed_unpanelled);
						$("#completionsCount").text(data.count.completed);
						$("#abortedCount").text(data.count.aborted);
					});
			});
		});
   </script>

@endpush
