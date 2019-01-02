@extends('dashboards._dashboard')

@section('heading')
    <div class="row">
        <h1 class="col-sm-10">Home</h1>
        <div class="col-sm-13">
            <div class="col-sm-24 warning-box p-2" style="">
                <section>
                    <image img src="/img/icons/notification.png" alt=""></image>
                    <a href="/service-feedback">{{$dashData['lowScoringFeedback']}} New Low Scoring Feedback &rsaquo;&rsaquo;</a>
                </section>
                <hr />
                <section>
                    <image img src="/img/icons/notification.png" alt=""></image>
                    <a href="/feedback">3 New Solicitor no contact cases &rsaquo;&rsaquo;</a>
                </section>
            </div>
        </div>
    </div>
@endsection

@section('dashboard')
    <div class="row" id="branchPerformanceBusinessOwner">
    <div class="col-sm-1"></div>
    <div class="panel col-sm-22">
        <div class="row">
            <div class="col-sm-5">
                <section><h4><strong>Branch Performance</strong></h4>
                    <p>MTD</p>
                </section>
            </div>
            <div class="col-sm-6">
                <label class="listing-label">Branch:</label>
                <select class="listing-select branch-performance-filter" id="branchesFilter">
                    <option value="">View All</option>
                    @foreach($dashData['branches'] as $branch)
                        <option value="{{ $branch->id }}">{{ $branch->Agency . ": " . $branch->Branch }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-10">
                <div class="row">
                   @include('partials._date-filters')
                </div>
            </div>
            <div class="col-sm-3">
                <button id="resetFiltersBtn" class="col-sm-24 success-button">Reset</button>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="notification-circle primary kpi @if($dashData['kpis']->prospect === 0)kpi-empty @endif" id="prospect">
                    <p>
                        <span id="prospectsCount">{{ $dashData['kpis']->prospect }}</span>
                        <br>Prospects
                    </p>
                </div>
            </div>
            <div class="col-sm-1"></div>
            <div class="col-sm-4">
                <div class="notification-circle instructions-kpi kpi @if($dashData['kpis']->instructed === 0)kpi-empty @endif" id="instructed">
                    <p>
                        <span id="instructionsCount">{{ $dashData['kpis']->instructed }}</span>
                        <br>Instructions
                    </p>
                </div>
            </div>
            <div class="col-sm-1"></div>
            <div class="col-sm-4">
                <div class="notification-circle darker-grey kpi @if($dashData['kpis']->instructed_unpanelled === 0)kpi-empty @endif" id="instructed_unpanelled">
                    <p>
                        <span id="unpanelledInstructionsCount">{{ $dashData['kpis']->instructed_unpanelled }}</span>
                        <br>Unpanelled Instructions
                    </p>
                </div>
            </div>
            <div class="col-sm-1"></div>
            <div class="col-sm-4">
                <div class="notification-circle success kpi @if($dashData['kpis']->completed === 0)kpi-empty @endif" id="completed">
                    <p>
                        <span id="completionsCount">{{ $dashData['kpis']->completed }}</span>
                        <br>Completions
                    </p>
                </div>
            </div>
            <div class="col-sm-1"></div>
            <div class="col-sm-4">
                <div class="notification-circle warning kpi @if($dashData['kpis']->aborted === 0)kpi-empty @endif" id="aborted">
                    <p>
                        <span id="abortedCount">{{ $dashData['kpis']->aborted }}</span>
                        <br>Aborted
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
    <div class="row" id="Row1">
        <div class="col-sm-1"></div>
        <div class="panel col-sm-7">
            <section><strong>Current Snapshot</strong></section>
            <div class="row">
                <div class="col-sm-24 data-row">
                    <p>Prospects<span class="dr-figure warning-font" id="">00</span></p>
                </div>
                <div class="col-sm-24 data-row">
                    <p>Active Cases<span class="dr-figure warning-font" id="">00</span></p>
                </div>
                <div class="col-sm-24 data-row">
                    <p>Exchanged Cases<span class="dr-figure warning-font" id="">00</span></p>
                </div>
            </div>
        </div>
        <div class="col-sm-1"></div>
        <div class="col-sm-7">
            <ul class="nav nav-tabs">
                <li class="nav-item" style="width:50%">
                    <a data-toggle="casepipeline" class="nav-link active" href="#">Case Pipeline (£)</a>
                </li>
                <li class="nav-item" style="width:50%">
                    <a data-toggle="revenue" class="nav-link" href="#">Revenue (YTD)</a>
                </li>
            </ul>
            <section id="casepipeline" class="clean-panel col-sm-23">
                <div class="panel-body">
                    <div class="col-sm-24 data-row" style="position:relative;">
                        <p>
                            Active Pipeline
                            <span class="dr-figure warning-font" id="" style="border:solid red 1px;">£0,000</span>
                        </p>
                    </div>
                    <div class="col-sm-24 data-row" style="position:fixed;">
                        <p>
                            Unpanelled Pipeline
                            <span class="dr-figure warning-font" id="">£0,000</span>
                        </p>
                    </div>
                    <div class="col-sm-24 data-row" style="position:absolute;">
                        <p>
                            <strong>Total Potential</strong>
                            <span class="dr-figure warning-font" id="">£0,000</span>
                        </p>
                    </div>
                </div>
            </section>
            <section id="revenue" class="clean-panel col-sm-23">
            </section>
        </div>
        <div class="col-sm-1"></div>
        <div class="panel col-sm-6">
            <section>
                <h5><strong>Average Completion</strong> (days)</h5>
                <div class="row">
                    <div class="col-sm-24 data-row">
                        <p>
                            Sale
                            <span class="dr-figure warning-font" id="">00</span>
                        </p>
                    </div>
                    <div class="col-sm-24 data-row">
                        <p>
                            Purchase
                            <span class="dr-figure success-font" id="">00</span>
                        </p>
                    </div>
                    <div class="col-sm-24 data-row">
                        <p>
                            <strong>Average</strong>
                            <span class="dr-figure success-font" id="">00</span>
                        </p>
                    </div>
                </div>
            </section>
        </div>
        <div class="col-sm-1"></div>
    </div>
    <div class="row" id="Row2">
    <div class="col-sm-1"></div>
    <div class="panel col-sm-7" name="Opportunity-Reporting">
        <section><strong>Opportunity Reporting</strong> MTD</section>
        <div class="row">
            <div class="col-sm-5">
                <label class="listing-label">Branch:</label>
                <select class="listing-select branch-performance-filter" id="branchesFilter">
                    <option value="">View All</option>
                    <option value=""></option>
                </select>
                <label class="listing-label">Date:</label>
                <input type="text" class="listing-text datepicker branch-performance-filter" id="dateFilter" placeholder="Please select" >
                <p id="date" class="hidden">{{ strtotime('midnight first day of this month') }}</p>
                <div style="margin-top:15%;">
                    <button id="" class="col-sm-16 success-button">Create Report</button>
                </div>
            </div>
            <div class="col-sm-9"></div>
            <div class="col-sm-6">
                <div class="notification-circle success">
                    <p>
                        <span></span>
                        <br/>Total
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-1"></div>
    <div class="panel col-sm-7" name="Solicitor-Activity">
        <section><strong>Solicitor Activity</strong><br>Contact over last 7 days</section>
        <div class="row">
            <div class="col-sm-3"></div>
            <div name="exclamation-icon">
                <div>
                    <i class="far fa-exclamation-triangle fa-5x" style="color:green"></i>
                </div>
                <div>
                    <section style="background-color:green;"><p align="center"; style="color:white"><strong>93%</strong></p></section>
                </div>
            </div>
            <div name="text">
                <section><strong>24 out of 27 active cases</strong><br>have had a solciitor<br>contact in the last 7 days</section>
            </div>
        </div>
    </div>
    <div class="col-sm-1"></div>
    <div class="panel col-sm-6" name="Service-FeedBack">
        <section style="margin-left:2%;"><strong>Service Feedback</strong><br>MTD</section>
        <div class="row">
            <div class="col-sm-1"></div>
            <div class="col-sm-10">
                <div class="colorbox mr-3 float-left" style="background:#B7C582;"></div>
                <strong>Staff Feedback on Account Manager(s)</strong><br>
                <i>
								<span id="agentFeedback">{{number_format($dashData['agentFeedbackAverageTCP'])}}
								</span> Average
                </i>
                <br>
                <br>
                <br>
                <div class="mr-3 float-left colorbox" style="background:#919D66;"></div>
                <div>
                    <strong>Customer Feedback</strong><br>
                    <i>
									<span id="agentFeedback">{{ number_format($dashData['customerFeedbackAverageTCP'])}}
									</span> Average
                    </i>
                </div>
            </div>
            <div class="col-sm-11">
                <div class="notification-circle success">
                    <p>
                        <span>{{ $dashData['feedbackTotal'] }}</span>
                        <br/>Total
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
	<script type="text/javascript">
		$(document).ready(function() {

			$('.nav-link').click(function(e) {
				e.preventDefault();
				$('.nav-link').removeClass('active');
				$(this).addClass('active');
				$('section.clean-panel').not('#' + $(this).attr('data-toggle')).addClass('hidden');
				$('#' + $(this).attr('data-toggle')).removeClass('hidden');
			});

			$("#branchPerformanceBusinessOwner .branch-performance-filter").change(function() {

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

                dateFrom = $('#dateFromFilter').val();

                if(dateFrom != '') {
                    $("#showingFrom").text('Showing from ' + dateFrom);
                    $("#dateFrom").text(dateFromUnix);
                    $("#dateTo").text(dateToUnix);
                } else {
                    $("#showingFrom").text('Showing MTD (default)');
                    $("#dateFrom").text({{ strtotime('midnight first day of this month') }});
                    $("#dateTo").text('');
                }

                const branchId = $("#branchesFilter").val();

                const data = {
                    branchId : branchId,
                    dateFrom : dateFromUnix,
                    dateTo : dateToUnix
                };

				const ajaxRequest = tcp.xhr.get('/branch/kpis-ajax-business-owner', data);
				ajaxRequest.done(function(data) {
					$("#prospectsCount").text(data.branchPerformanceKpi.prospect);
					$("#instructionsCount").text(data.branchPerformanceKpi.instructed);
					$("#unpanelledInstructionsCount").text(data.branchPerformanceKpi.instructed_unpanelled);
					$("#completionsCount").text(data.branchPerformanceKpi.completed);
					$("#abortedCount").text(data.branchPerformanceKpi.aborted);
				});

				ajaxRequest.fail(function(data) {

				});
			});
		});
	</script>

@include('branches.performance.kpis-js')

@endpush
