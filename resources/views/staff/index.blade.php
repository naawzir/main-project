@extends('layouts.app')
@section('content')

	<div class="col-sm-20 window">
	   	@include('layouts.breadcrumb', [
			"breadcrumbs" =>
			   [
				   "staff",
			   ]
			]
	   	)

	  	<div id="page-header" class="col-sm-23">
		  	<h1>Staff Performance</h1>
	  	</div>

		<hr/>

	    <div id="content-area">
			<div class="panel col-sm-23">
				<div class="panel-body">
					<div class="row">
						<div class="col-sm-6">
							<label>Select Staff: </label>
							<select id="account-managers">
								<option>Please Select</option>
								@foreach($accountMans as $accMans)
									<option value="{{ $accMans->id }}">{{ $accMans->forenames.' '.$accMans->surname }}</option>
								@endforeach
							</select>
						</div>

						<div class="col-sm-15">
							<div class="row">
								@include('partials._date-filters')
							</div>
						</div>

						<div class="col-sm-3">
							<button id="resetFiltersBtn" class="col-sm-24 success-button">Reset</button>
						</div>
					</div>
				</div>
			</div>

				<div class="panel col-sm-16 mr-5">

					<h5><strong>Performance</strong> MTD</h5>
					<div class="row">

						<div class="left-col col-sm-8">
							<div class="row">
								<div class="col-sm-12">@svg('donut-chart')</div>
								<div class="col-sm-12">@svg('donut-chart')</div>
							</div>
						</div>

						<div class="left-col col-sm-8">
							<div class="row">
								<div class="col-sm-12">@svg('donut-chart')</div>
								<div class="col-sm-12">@svg('donut-chart')</div>
							</div>
						</div>

						<div class="left-col col-sm-8">
							<div class="row">
								<div class="col-sm-12">
									<div class="notification-circle warning">
										<p><span>00</span><br/>Test Data</p>
									</div>
								</div>
								<div class="col-sm-12">
									<div class="notification-circle warning">
										<p><span>00</span><br/>Test Data</p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="panel col-sm-6">
					<div class="col-sm-24">
						<h5><strong>Activity</strong></h5>
						@if($viewData)
							<div class="row">
								<div class="col-sm-24 data-row">
									<p><a href="/cases/?clear=true">New Leads <span class="dr-figure alert-font" id="new-leads">{{ $viewData['currentActivity']['new'] }}</span></a></p>
								</div>
								<div class="col-sm-24 data-row">
									<p><a href="/cases/?clear=true">Unpanelled Instructions <span class="dr-figure darker-gray-font" id="unpanelled">{{ $viewData['currentActivity']['unpanelled'] }}</span></a></p>
								</div>
								<div class="col-sm-24 data-row">
									<p><a href="/cases/?clear=true">Completions <span class="dr-figure success-font" id="completions">{{ $viewData['currentActivity']['completions'] }}</span></a></p>
								</div>
								<div class="col-sm-24 data-row">
									<p><a href="/cases/?clear=true">Aborted <span class="dr-figure warning-font" id="aborted">{{ $viewData['currentActivity']['aborted'] }}</span></a></p>
								</div>
							</div>
						@else {{-- I don't think this block of code is needed --}}
						<div class="row">
							<div class="col-sm-24 data-row">
								<p><a>New Leads <span class="dr-figure alert-font" id="new-leads">00</span></a></p>
							</div>
							<div class="col-sm-24 data-row">
								<p><a>Unpanelled Instructions <span class="dr-figure darker-gray-font" id="unpanelled">00</span></a></p>
							</div>
							<div class="col-sm-24 data-row">
								<p><a>Completions <span class="dr-figure success-font" id="completions">00</span></a></p>
							</div>
							<div class="col-sm-24 data-row">
								<p><a>Aborted <span class="dr-figure warning-font" id="aborted">00</span></a></p>
							</div>
						</div>
						@endif
					</div>
				</div>


				<div class="panel col-sm-16 mr-5">
					<h5><strong>Conversion</strong> MTD</h5>

					<div class="row">
						<div class="col-sm-6">
							<div class="notification-circle primary">
								<p><span>00</span><br/>Test Data</p>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="notification-circle primary-dark">
								<p><span>00</span><br/>Test Data</p>
							</div>
						</div>
						<div class="col-sm-6">@svg('donut-chart')</div>
					</div>
				</div>

				<div class="prospects panel col-sm-6">
					<h5><strong>Prospects</strong></h5>
					<div class="row">

						<!-- this whole element should be built with JS affecting the colors of the SVG and the totals in the spans -->

						<div class="col-sm-8">
							@svg('notification', ['id' => 'prospects-notification'])
							<span class="callout-info">00&#37;</span>
						</div>

						<div class="col-sm-16">
							<p><span>0 out of 0</span> have had contact in last 7 days</p>
						</div>

					</div>
				</div>

				<div class="panel col-sm-23">
					<h5><strong>Branches</strong></h5>
				</div>

	   	  	</div>

@endsection

@push('scripts')
	<script type="text/javascript">

		$(document).ready(function() {

			$('.datepicker').datepicker({
                changeMonth: true,
                changeYear: true,
                firstDay: 1,
                dateFormat: 'dd/mm/yy'
			});

            $(".branch-performance-filter").change(function() {

                let dateFrom = $('#dateFromFilter').val();
                dateFrom = dateFrom.substr(3, 2) + "/" + dateFrom.substr(0, 2) + "/" + dateFrom.substr(6, 4);
                const dateFromUnix = Math.round((new Date(dateFrom)).getTime() / 1000);

                let dateTo = $('#dateToFilter').val();
                dateTo = dateTo.substr(3, 2) + "/" + dateTo.substr(0, 2) + "/" + dateTo.substr(6, 4);
                const dateToUnix = Math.round((new Date(dateTo)).getTime() / 1000);

                if(dateFromUnix > dateToUnix) {
                    dateFrom = $('#dateToFilter').val();
                    dateTo = $('#dateFromFilter').val();

                    $('#dateFromFilter').val(dateFrom);
                    $('#dateToFilter').val(dateTo);
                }

            });

		});

	</script>
@endpush
