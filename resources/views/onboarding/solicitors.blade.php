@extends('layouts.app')
@section('content')

	<div class="col-sm-20 window">

    @include('layouts.breadcrumb', [
    	"breadcrumbs" =>
    		[
    			"solicitors",
    			"onboarding"
    		]
    	]
    )
		<h1>Onboarding</h1>
			<a href="/solicitors/create/">
				<button class="success-button view-button" style="float:right; margin-right:30px; margin-bottom:10px;">Add New Solicitor</button>
			</a>
		<div id="content-area" class="panel col-sm-23">
			<div class="panel-body">
				<table class="table table-bordered nohighlight" id="solicitorTable">
					<thead>
					<tr>
						<th id="solicitor">Solicitor</th>
						<th id="count">Count</th>
						<th id="status">Status</th>
						<th id="viewmore"></th>
					</tr>
					</thead>
                    <tbody>
                    @foreach ($offices as $office)
                        <tr>
                            <td>{{$office->solicitor->name}}</td>
                            <td>{{$office->countOnboardingOffices($isBDM, $office->solicitor_id)}}</td>
                            <td>{{$office->status}}</td>
                            <td align="right">
                                @if ($office->countOnboardingOffices($isBDM, $office->solicitor_id) > 1)
								    <button data-type="solicitor" data-id="{{$office->solicitor->slug}}" type="button" class="success-button view-button">View</button>
                                @else
                                    <button data-type="office" data-id="{{$office->slug}}" type="button" class="success-button view-button">View</button>
                                @endif
							</td>
                        </tr>
                    @endforeach
                    </tbody>
				</table>
			</div>
		</div>
	</div>
	@push('scripts')
        <script type="text/javascript">
            $(document).ready(() =>
            {
                $('.view-button').on('click', function () {
                    if ($(this).attr('data-type') === 'solicitor') {
                        window.location = '/solicitors/onboarding/' + $(this).attr('data-id') + '/offices/';
                    }

                    else {
                        window.location = '/solicitors/office/' + $(this).attr('data-id');
                    }
                });
            });
        </script>
	@endpush
@endsection
