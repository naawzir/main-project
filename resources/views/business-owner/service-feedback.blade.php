@extends('layouts.app')
@section('content')
<div class="col-sm-20 window">
	<div class="row">
		<div class="col-sm-24">
			@include('layouts.breadcrumb',
                ['breadcrumbs' =>
                    [
                     'service-feedback' => 'Service Feedback',
                    ]
                ]
             )
			<div class="row" id="Title">
				<div class="col-sm-17">
					<h1><strong>Service Feedback</strong></h1>
				</div>
			</div>
			<div class="row" id="CustomerFeedbackOnSolicitor">
				<div class="col-sm-1"></div>
				<div class="panel col-sm-22">
					<div class="row">
						<section><h4><strong>Customer Feedback <br>On Solicitor</strong></h4>
						<p>MTD</p></section>
					</div>
					<div class="row">
						<div class="col-sm-1"></div>
						<div class="col-sm-4">
							<div class="notification-circle success kpi" id="completed">
								<p>
								<span id="completionsCount">{{$customerHighScoreCount}}</span>
								<br>High Scores
								</p>
							</div>
						</div>
						<div class="col-sm-1"></div>
						<div class="col-sm-4">
							<div class="notification-circle warning kpi" id="aborted">
								<p>
								<span id="abortedCount">{{$customerLowScoreCount}}</span>
								<br>Low Scores
								</p>
							</div>
						</div>
                        <div class="col-sm-1"></div>
                        <div class="col-sm-4">
						<div class="col-sm-12">
							<a class="kpi-empty" href="#">
								@svg('donut-chart', ['id' => 'AverageScorecustomer']) 
							</a>
						</div>
						</div>
                        <div class="col-sm-1"></div>
                        <div class="col-sm-4">
						<div class="col-sm-12">
							<a class="kpi-empty" href="#">
								@svg('donut-chart', ['id' => 'customerResponseRate']) 
							</a>
						</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row" id="StaffFeedbackOnSolicitor">
				<div class="col-sm-1"></div>
				<div class="panel col-sm-22">
					<div class="row">
						<section><h4><strong>Staff Feedback <br>On Account Manager</strong></h4>
						<p>MTD</p></section>
					</div>
					<div class="row">
						<div class="col-sm-1"></div>
						<div class="col-sm-4">
							<div class="notification-circle success kpi" id="completed">
								<p>
								<span id="completionsCount">{{$agentHighScoreCount}}</span>
								<br>High Scores
								</p>
							</div>
						</div>
						<div class="col-sm-1"></div>
						<div class="col-sm-4">
							<div class="notification-circle warning kpi" id="aborted">
								<p>
								<span id="abortedCount">{{$agentLowScoreCount}}</span>
								<br>Low Scores
								</p>
							</div>
						</div>
                        <div class="col-sm-1"></div>
                        <div class="col-sm-4">
						<div class="col-sm-12">
							<a class="kpi-empty" href="#">
								@svg('donut-chart', ['id' => 'AverageScoreAgent']) 
							</a>
						</div>
						</div>
                        <div class="col-sm-1"></div>
                        <div class="col-sm-4">
						<div class="col-sm-12">
							<a class="kpi-empty" href="#">
								@svg('donut-chart', ['id' => 'agentResponseRate'])
							</a>
						</div>
						</div>
					</div>
				</div>
			</div>
            <div class="row">
				<div class="col-sm-1"></div>
				<div class="panel col-sm-22">

					<div class="row">
						<section>
							<h4><strong>Low Scoring Customer Feedback</strong></h4>
						</section>
					</div>

					<table class="table table-bordered" id="lowScoringCustomerFeedback">
						<thead>
							<tr>
								<th>Created</th>
								<th>Case Ref</th>
								<th>Transaction</th>
								<th>Customer Name</th>
								<th>Solicitor</th>
								<th>Score</th>
							</tr>
						</thead>
					</table>

				</div>
			</div>     
		</div>
	</div>
</div>
@endsection

@push('scripts')
	<script>
		const ResponseRateCustomer = @json($customerResponseRate['responseRateCustomer']);
		fillDonut('#customerResponseRate', ResponseRateCustomer);
		
		const ResponseRateAgent = @json($agentResponseRate['responseRateAgent']);
		fillDonut('#agentResponseRate', ResponseRateAgent);
		$(".chart-number").append('%');

		const AverageScoreCustomer = @json($customerFeedbackAverageTCPDonut['AverageScoreCustomer']);
		fillDonut('#AverageScorecustomer', AverageScoreCustomer);

		const AverageScoreAgent = @json($agentFeedbackAverageTCPDonut['AverageScoreAgent']);
		fillDonut('#AverageScoreAgent', AverageScoreAgent);

        var datatables_info =
            [{
                url: '/feedback/get-low-scoring-customer-feedback',
                dataTableID: '#lowScoringCustomerFeedback',
                ordering: [[0, "asc"]],
                stateSave: true,
                cols :
                    [
                        {
                            data: 'date_created_raw',
                            name: 'date_created_raw',
                            render: function (data, type, full, meta) {
                                return full.date_created;
                            }
                        },
                        {
                            data: 'reference',
                            name: 'reference',
                            render: function(data, type, full, meta)
                            {
                                return '<span><a target="_blank" href="' + '/cases/' + full.case_slug + '">' + full.reference + '</a></span>';
                            }
                        },
                        {
                            data: 'transaction',
                            name: 'transaction'
                        },
                        {
                            data: 'Customer',
                            name: 'Customer'
                        },
                        {
                            data: 'Solicitor',
                            name: 'Solicitor'
                        },
                        {
                            data: 'score',
                            name: 'score',
                            render: function(data, type, full, meta)
                            {
                                return '<span class="caseReminderRed">' + full.score + '</span>';
                            }
                        }
                    ]
            }];

        makeDatatable(datatables_info);
	</script>
@endpush
