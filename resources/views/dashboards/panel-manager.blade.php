@extends('dashboards._dashboard')

@section('heading')
	<h1>Home</h1>
@endsection

@section('dashboard')
	<!--OTHER CONTENT GOES HERE -->
	<div class="row col-sm-23">
		<section class="panel col-sm-12">
			<h4><strong>Service Feedback</strong></h4>
			<h6>MTD</h6>
			<div class="row">
				<div class="col-sm-1"></div>
				<div class="col-sm-10" >
					<div class="colorbox mr-3 float-left " style="background:#B7C582;"></div>
					<strong>Customer Feedback on Solicitors</strong><br>
					<i>
						<span id="customerFeedback">
							{{ number_format($dashData['customerFeedbackAverage'], 1) / 2 }}
						</span> Average
					</i>
					<br>
					<br>
					<br>
					<div class="mr-3 float-left colorbox" style="background:#919D66;"></div>
					<div>
						<strong>Agent Feedback on Solicitors</strong><br>
						<i>
							<span id="agentFeedback">
								{{ number_format($dashData['agentFeedbackAverage'], 1) }}
							</span> Average
						</i>
					</div>
				</div>
				<div class="col-sm-2"></div>
				<div class="notification-circle success">
					<p>
						<span>{{ $dashData['feedbackTotal'] }}</span>
						<br/>Total
					</p>
				</div>
				{{--<div class="col-sm-11" >
					@svg('donut-chart', ['id' => 'feedbackOnSolicitors'])
				</div>--}}
			</div>
		</section>
		<div class="row col-sm-24">
			<section class="panel col-sm-12">
				<h4><strong>Missing Milestones</strong></h4>
				<div class="row">
					<div class="col-sm-24 data-row">
						<p>
							<a href="/cases/?clear=true">Pending Instruction Confirmation
								<span class="dr-figure warning-font" id="new-leads">00</span>
							</a>
						</p>
					</div>
					<div class="col-sm-24 data-row">
						<p>
							<a href="/cases/?clear=true">Welcome Calls Missed
								<span class="dr-figure success-font" id="unpanelled">00</span>
							</a>
						</p>
					</div>
					<div class="col-sm-24 data-row">
						<p>
							<a href="/cases/?clear=true">Welcome Packs Not Set
								<span class="dr-figure success-font" id="completions">00</span>
							</a>
						</p>
					</div>
					<div class="col-sm-24 data-row">
						<p>
							<a href="/cases/?clear=true">Completion Date Not Set
								<span class="dr-figure warning-font" id="aborted">00</span>
							</a>
						</p>
					</div>
				</div>
			</section>
			<section class="col-sm-1"></section>
			<section class="panel col-sm-11">
				<h4><strong>Agent Update Requests</strong></h4>
				<div class="row">
					<div class="col-sm-8">
						<div class="notification-circle success">
							<p>
								<span>{{ $dashData['updateRequestsTotal'] }}</span>
								<br/>Update Requests
							</p>
						</div>
					</div>
					<div class="col-sm-8">
						<div class="notification-circle warning">
							<p>
								<span>00</span>
								<br/>Missed
							</p>
						</div>
					</div>
					<div class="col-sm-8">
						@svg('donut-chart', ['id' => 'dailyInstructions'])
					</div>
			</section>
		</div>
	</div>
	<!--OTHER CONTENT ENDS HERE -->
	<div class="row">
				<ul class="nav nav-tabs">
					<li class="nav-item">
						<a data-toggle="reminders" class="nav-link active" href="#"><strong>Reminders ({{$dashData['reminderTasksCount']}})</strong></a>
					</li>
					<li class="nav-item">
						<a data-toggle="alerts" class="nav-link" href="#"><strong>Alerts ({{$dashData['alertTasksCount']}})</strong></a>
					</li>
				</ul>
				<!-- Full width panel -->
				<section id="reminders" class="clean-panel col-sm-23">
					<div class="panel-body">
						<table class="table table-bordered" id="reminderTasksTable">
							<thead>
								<tr>
									<th id="UpdateRequestDate">Due</th>
									<th id="type">Type</th>
									<th id="note">Note</th>
									<th id="view"></th>
								</tr>
							</thead>
						</table>
					</div>
				</section>
				<section id="alerts" class="clean-panel col-sm-23 hidden">
					<div class="panel-body">
						<table class="table table-bordered" id="alertTasksTable">
							<thead>
								<tr>
									<th id="UpdateRequestDate">Due</th>
									<th id="type">Type</th>
									<th id="note">Note</th>
									<th id="view"></th>
								</tr>
							</thead>
						</table>
					</div>
				</section>
			</div>
@endsection


@push('scripts')

	<script type="text/javascript">

        $(document).ready(function()
        {
			var datatables_info =
				[{
					url: '/tasks/get-tasks-for-user/reminder',
					dataTableID: '#reminderTasksTable',
					ordering: [[0, "desc"]],
					stateSave: true,
					dom: `tl`,
					lengthmenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
					displaylength: -1,
					cols :
					[{
						data: 'date_due',
						name: 'date_due',
						render: function(data, type, full, meta)
						{
							return full.due;
						}
					},
					{
						data: 'type',
						name: 'type',
						render: function(data, type, full, meta)
						{
							var dataType = toTitleCase(full.type);
							return '<span class="' + typeArray[dataType] + '">' + dataType + '</span>';
						}
					},
					{
						data: 'notes',
						name: 'notes',
						render: function(data, type, full, meta)
						{
							return strip(full.notes);
						}
					},
					{
						data: 'id',
						name: 'id',
						render: function(data, type, full, meta)
						{
							return '<a href="/tasks/'+ full.slug +'"><button class="success-button">View</button></a>';
						}
					}]
				},
					{
						url: '/tasks/get-tasks-for-user/alert',
						dataTableID: '#alertTasksTable',
						ordering: [[0, "desc"]],
						stateSave: true,
						dom: `tl`,
						lengthmenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
						displaylength: -1,
						cols :
						[{
							data: 'date_due',
							name: 'date_due',
							render: function(data, type, full, meta)
							{
								return full.due;
							}
						},
						{
							data: 'type',
							name: 'type',
							render: function(data, type, full, meta)
							{
								var dataType = toTitleCase(full.type);
								return '<span class="' + typeArray[dataType] + '">' + dataType + '</span>';
							}
						},
						{
							data: 'notes',
							name: 'notes',
							render: function(data, type, full, meta)
							{
								return strip(full.notes);
							}
						},
						{
							data: 'id',
							name: 'id',
							render: function(data, type, full, meta)
							{
								return '<a href="/tasks/'+ full.slug +'"><button class="success-button">View</button></a>';
							}
						}]
					}];

			makeDatatable(datatables_info);

            $('.nav-link').click(function(e){
                e.preventDefault();
                $('.nav-link').removeClass('active');
                $(this).addClass('active');
                $('section.clean-panel').not('#' + $(this).attr('data-toggle')).addClass('hidden');
                $('#' + $(this).attr('data-toggle')).removeClass('hidden');
            });
        });
	</script>

@endpush
