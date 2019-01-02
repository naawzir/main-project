@extends('dashboards._dashboard')

@section('heading')
	<div class="row" id="Title">
		<h1 class="col-sm-10">Home</h1>
		<div class="col-sm-13">
			<div class="col-sm-24 warning-box p-2">
				<section>
					<img src="/img/icons/notification.png" alt="">
					<a href="/service-feedback">2 New Low Scoring Feedback &rsaquo;&rsaquo;</a>
				</section>
				<hr />
				<section>
					<img src="/img/icons/notification.png" alt="">
					<a href="/feedback" style="text-color:black">3 New Solicitor no contact cases &rsaquo;&rsaquo;</a>
				</section>
			</div>
		</div>
	</div>
@endsection

@section('dashboard')
	<div class="row" id="Branches">
		<div class="col-sm-1"></div>
		<div class="panel col-sm-22">
			<section><h4><strong>Branches</strong></h4>
				<h5><strong>MTD</strong></h5>

				<div class="row">
					<div class="col-sm-4">
						<div class="notification-circle primary kpi" id="prospect">
							<p>
								<span id="prospectsCount">{{ $dashData['kpis']['prospect'] }}</span>
								<br>Prospects

							</p>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="notification-circle instructions-kpi kpi" id="instructed">
							<p><span id="instructionsCount">{{ $dashData['kpis']['instructed'] }}</span>
								<br>Instructions
							</p>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="notification-circle instructions-kpi kpi" id="Conversion">
							<p><span id="Conversion"></span>
								<br>Conversion
							</p>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="notification-circle darker-grey kpi" id="instructed_unpanelled">
							<p>
								<span id="unpanelledInstructionsCount">{{ $dashData['kpis']['instructed_unpanelled'] }}</span>
								<br>Unpanelled Instructions
							</p>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="notification-circle success kpi" id="completed">
							<p>
								<span id="completionsCount">{{ $dashData['kpis']['completed'] }}</span>
								<br>Completions
							</p>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="notification-circle warning kpi" id="aborted">
							<p>
								<span id="abortedCount">{{ $dashData['kpis']['aborted'] }}</span>
								<br>Aborted
							</p>
						</div>
					</div>
				</div>
			</section>
		</div>
	</div>
	<div class="row" id="OutstandingTaskLeadSource">
		<div class="col-sm-1"></div>
		<div class="panel col-sm-10" id="OutstandingTasks">
			<section><h4><strong>Outstanding Tasks</strong></h4>
				<div class="row">
					<div class="col-sm-24 data-row">
						<p>New Lead
							<span class="dr-figure warning-font" id="">00</span>
						</p>
					</div>
					<div class="col-sm-24 data-row">
						<p>Chase Prospect
							<span class="dr-figure warning-font" id="">00</span>
						</p>
					</div>
					<div class="col-sm-24 data-row">
						<p>Branch Contact
							<span class="dr-figure primary-font" id="">00</span>
						</p>
					</div>
					<div class="col-sm-24 data-row">
						<p>Solicitor Contact
							<span class="dr-figure success-font" id="">00</span>
						</p>
					</div>
				</div>
		</div>
		<div class="col-sm-1"></div>
		<div class="panel col-sm-11" id="LeadSource">
			<h4><strong>Lead Source</strong></h4>
			<div class="row">
				<div class="col-sm-24 data-row">
					<p>New Take On
						<span class="dr-figure warning-font" id="">00</span>
					</p>
				</div>
				<div class="col-sm-24 data-row">
					<p>On The Market
						<span class="dr-figure warning-font" id="">00</span>
					</p>
				</div>
				<div class="col-sm-24 data-row">
					<p>Buyer SSTC
						<span class="dr-figure primary-font" id="">00</span>
					</p>
				</div>
				<div class="col-sm-24 data-row">
					<p>Seller SSTC
						<span class="dr-figure primary-font" id="">00</span>
					</p>
				</div>
			</div>
		</div>
	</div>
	<div class="row" id="SolcitorActivityServiceFeedback">
		<div class="col-sm-1"></div>
		<div class="panel col-sm-8" id="SolicitorActivity">
			<section><h4><strong>Solcitor Activity</strong></h4>
				<h6><strong>Contact over last 7 days</strong></h6>
				<div class="row">
					<div class="col-sm-3"></div>
					<div name="exclamation-icon">
						<div>
							<i class="far fa-exclamation-triangle fa-5x" style="color:#DE7163"></i>
						</div>
						<div>
							<section style="background-color:#DE7163;"><p align="center"; style="color:white"><strong>93%</strong></p></section>
						</div>
					</div>
					<div class="col-sm-3"></div>
					<div name="text">
						<section><strong>24 out of 27 active cases</strong><br>have had a solciitor<br>contact in the last 7 days</section>
					</div>
				</div>
            </section>
		</div>
		<div class="col-sm-1"></div>
		<div class="panel col-sm-13" if="ServiceFeedback">
			<section><h4><strong>Service Feedback</strong></h4></section>
			<h6><strong>MTD</strong></h6>
		</div>
	</div>
    @yield('tasks')
@endsection
