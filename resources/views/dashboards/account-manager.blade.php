@extends('dashboards._dashboard')

@section('heading')
	<h1>Home</h1>
@endsection

@section('dashboard')
		<div class="row">
			<!-- 2/3rd panel && 1/3rd panel -->
			<section class="panel col-sm-15">
				<h5><strong>My Performance</strong></h5>
				<div class="row">

					<div class="col-sm-8 row left-col">
						<div class="col-sm-24 ml-4">
							<p><strong>This month</strong></p>
						</div>
						<div class="col-sm-12">
							<a class="kpi-empty" href="#">
								@svg('donut-chart', ['id' => 'mtdTarget'])
							</a>
						</div>
						<div class="col-sm-12">
							<a href='/cases?status=instructed&transaction=&account-manager={{ Auth::user()->id }}&agent-id=&user-id-agent=&date={{ strtotime('midnight first day of this month') }}'>
								@svg('donut-chart', ['id' => 'mtdInstructions'])
							</a>
						</div>
					</div>

					<div class="col-sm-8 row left-col">
						<div class="col-sm-24 ml-4">
							<p><strong>Daily</strong></p>
						</div>
						<div class="col-sm-12">
							<a class="kpi-empty">
								@svg('donut-chart', ['id' => 'dailyTarget'])
							</a>
						</div>
						<div class="col-sm-12">
							<a class="kpi-empty">
								@svg('donut-chart', ['id' => 'dailyInstructions'])
							</a>
						</div>
					</div>

					<div class="col-sm-8 row left-col">
						<div class="col-sm-24 ml-4">
							<p><strong>Predicted Finish</strong></p>
						</div>
						<div class="col-sm-12">
							<div class="notification-circle warning">
								<p><span>{{ $dashData['predictedFinishRisk'] }}</span>
									<br/>Risk
								</p>
							</div>
						</div>
						<div class="col-sm-12">
							<div class="notification-circle warning">
								<p><span>{{ $dashData['predictedFinish'] }}</span>
									<br/>Instructions
								</p>
							</div>
						</div>
					</div>

				</div>
			</section>
			<section class="col-sm-1"></section>
			<section class="panel col-sm-7">
				<h5><strong>Current Activity</strong></h5>
				<div class="row">
					<div class="col-sm-24 data-row">
						<p><a href="/cases/?clear=true">New Leads
								<span class="dr-figure alert-font" id="new-leads">{{ $dashData['currentActivity']['prospect'] }}</span>
							</a>
						</p>
					</div>

					<div class="col-sm-24 data-row">
						<p><a href="/cases/?clear=true">Unpanelled Instructions
								<span class="dr-figure darker-gray-font" id="unpanelled">{{ $dashData['currentActivity']['instructed_unpanelled'] }}</span>
							</a>
						</p>
					</div>

					<div class="col-sm-24 data-row"><p>
							<a href="/cases/?clear=true">Completions
								<span class="dr-figure success-font" id="completions">{{ $dashData['currentActivity']['completed'] }}</span>
							</a>
						</p>
					</div>

					<div class="col-sm-24 data-row"><p>
							<a href="/cases/?clear=true">Aborted
								<span class="dr-figure warning-font" id="aborted">{{ $dashData['currentActivity']['aborted'] }}</span>
							</a>
						</p>
					</div>
				</div>
			</section>

		</div>

		<div class="row">

				<ul class="nav nav-tabs">
				  <li class="nav-item">
				    <a data-toggle="case" class="nav-link active" href="#">Case Reminders ({{$dashData['caseTasksCount']}})</a>
				  </li>
				  <li class="nav-item">
				    <a data-toggle="branch" class="nav-link" href="#">Branch Reminders ({{$dashData['branchTasksCount']}})</a>
				  </li>
				</ul>
			<!-- Full width panel -->
			<section id="case" class="clean-panel col-sm-23">
				<div class="panel-body">
					<table class="table table-bordered" id="caseTasksTable">
						<thead>
						<tr>
							<th id="due">Due</th>
							<th id="type">Type</th>
							<th id="note">Note</th>
							<th id="view"></th>
						</tr>
						</thead>
					</table>
				</div>
			</section>
			<section id="branch" class="clean-panel col-sm-23 hidden">
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

@push('scripts')

	<script type="text/javascript">

		window.jQuery(function($) {
			const mtdTarget = @json($dashData['kpis']['mtdTarget']);
			const mtdInstructions = @json($dashData['kpis']['mtdInstructions']);
            const dailyTarget = @json($dashData['kpis']['dailyTarget']);
            const dailyInstructions = @json($dashData['kpis']['dailyInstructions']);

			fillDonut('#mtdTarget', mtdTarget);
			fillDonut('#mtdInstructions', mtdInstructions);
			fillDonut('#dailyTarget', dailyTarget);
			fillDonut('#dailyInstructions', dailyInstructions);
        });

		$(document).ready(function()
		{
			var datatables_info =
			[{
				url: '/tasks/get-tasks-for-user/case',
				dataTableID: '#caseTasksTable',
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
				url: '/tasks/get-tasks-for-user/branch',
				dataTableID: '#branchTasksTable',
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
					data: 'branch',
					name: 'branch',
                    render: function(data, type, full, meta)
                    {
                        return '<span class="' + typeArray['Branch'] + '">' + full.branch + '</span>';
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
