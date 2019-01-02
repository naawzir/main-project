@extends('dashboards._dashboard')
@section('heading')
	<div class="row">
		<h1 class="col-sm-10">Home</h1>

		@if(strtotime($dashData['monthlyFeedbackLastCompleted']['date_created']) < strtotime('-30 days'))
			<div class="col-sm-13">
				<div class="col-sm-24 warning-box p-2" style="border-radius:15px">
					<p>Your feedback is important, don&#39;t forget to answer our monthly feedback <a href="/feedback">survey <span aria-hidden="true">&rsaquo;&rsaquo;</span></a></p>
				</div>
			</div>
		@endif
	</div>
@endsection
@section('dashboard')
	<div class="row">
		<section class="panel col-sm-11">
			<h4 class="ml-3">
				<strong>Activity</strong>
			</h4>

			<div class="row">
				<div class="col-sm-12">
					<div class="notification-circle primary agent-dashboard-kpi kpi @if($dashData['caseKpiFigures']['prospect'] === 0)kpi-empty @endif" id="prospect">
						<p>
							<span>{{ $dashData['caseKpiFigures']['prospect'] }}</span>
							<br/>Case Leads
						</p>
					</div>
				</div>

				<div class="col-sm-12">
					<div class="notification-circle agent-dashboard-kpi instructions-kpi kpi @if($dashData['caseKpiFigures']['instructed'] === 0)kpi-empty @endif" id="instructed">
						<p>
							<span>{{ $dashData['caseKpiFigures']['instructed'] }}</span>
							<br/>Instructions
						</p>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<div class="notification-circle darker-grey kpi agent-dashboard-kpi @if($dashData['caseKpiFigures']['instructed_unpanelled'] === 0)kpi-empty @endif" id="instructed_unpanelled">
						<p>
							<span>{{ $dashData['caseKpiFigures']['instructed_unpanelled'] }}</span>
							<br/>Unpanelled Instructions
						</p>
					</div>
				</div>
				<div class="col-sm-12">
					<div class="notification-circle success kpi agent-dashboard-kpi @if($dashData['caseKpiFigures']['completed'] === 0)kpi-empty @endif" id="completed">
						<p>
							<span>{{ $dashData['caseKpiFigures']['completed'] }}</span>
							<br/>Completions
						</p>
					</div>
				</div>
			</div>
		</section>
		<div class="col-sm-1"></div>
	<div class="autoplay col-sm-11" >
  		<div><img src="/images/office1.jpg" alt="" style="width:730px; height:380px;"></div>
  		<div><img src="/images/office2.jpg" alt="" style="width:730px; height:380px;"></div>
  		<div><img src="/images/office3.jpg" alt="" style="width:730px; height:380px;"></div>
	</div>
</div>
<div class="row">
	<section class="panel col-sm-23">
		<h4 class="ml-3">
			<strong>My Latest Active Cases</strong>
		</h4>
		<table class="table table-bordered" id="">
			<thead>
				<tr>
					<th id="">Created</th>
					<th id="">Case Ref</th>
					<th id="">Status</th>
					<th id="">Transaction</th>
					<th id="">Address</th>
					<th id="">Solicitor</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($dashData['activecases'] as $cases)
				<tr>
					<td>{{$cases->date_created}}</td>
					<td>{{$cases->reference}}</td>
					<td>{{ ucfirst($cases->status) }}</td>
					<td>{{ ucfirst($cases->transaction) }}</td>
					<td>{{$cases->TransactionAddress}}</td>
					<td>{{$cases->Solicitor}}</td>
				</tr>
				@endforeach
			</tbody>
		</table>
		<a href="/cases?status=&transaction=&account-manager=&agent-id=&date={{ strtotime('midnight first day of this month') }}&user-id-agent={{ Auth::user()->id }}">
			<button class="success-button">View All </button>
		</a>
	</section>
</div>
@endsection
@push('scripts')
@include('branches.performance.kpis-js')
	<script src="slick/slick.min.js"></script>
	<script>
		$('.autoplay').slick({
			slidesToShow: 1,
			slidesToScroll: 1,
			autoplay: true,
			fade: true,
			arrows: false,
			dots: true,
			autoplaySpeed: 5000,
		});
	</script>
@endpush
@push('styles')
	<link rel="stylesheet" href="{{ asset('slick/slick.css') }}" />
	<link rel="stylesheet" href="{{ asset('slick/slick-theme.css') }}" />
@endpush
