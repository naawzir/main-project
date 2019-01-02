@extends('layouts.app')

@section('content')

	<div class="col-sm-20 window">

		 @include('layouts.breadcrumb', [
		 	"breadcrumbs" =>
				[
					"solicitors",
				]
		 	]
		 )

		<div id="page-header" class="col-sm-23">
			<h1>Solicitor Market Place</h1>
		</div>

		<div id="added-content-area" class="clean-panel col-sm-23 row">

			<div class="row col-sm-24">
				<div class="col-sm-6">
					<label class="listing-label">Location</label>
					<input type="text" class="listing-text" id="filterPostcode" placeholder="Enter Postcode"/>
				</div>

				<div class="col-sm-6">
					<label class="listing-label">Search Radius</label>
					<select class="listing-select" id="filterRadius"></select>
				</div>

				<div class="col-sm-6">
					<label class="listing-label">Average Completion (Days)</label>
					<select class="listing-select" id="filterAverage"></select>
				</div>
			</div>
		</div>

		<div id="content-area" class="panel col-sm-23">
			<div class="panel-body">
				<table class="table table-bordered" id="solicitorTable">
					<thead>
					<tr>
						<th id="solicitor">Solicitor/Location</th>
						<th id="averagecompletion">Average Completion (Days)</th>
						<th id="agentrating">Average Rating</th>
						<th id="legalfees">Legal Fees</th>
						<th id="viewmore"></th>
					</tr>
					</thead>
				</table>
			</div>
		</div>

	</div>

@endsection
@push('scripts')
	<script type="text/javascript">
		let isbusiness = false;
		@if ($user_role->dashboard_title === 'business-owner')
        isbusiness = true;
		@endif
        $(document).ready(function()
        {
            var datatables_info =
                [{
                    url: '/solicitors/get-records',
                    dataTableID: '#solicitorTable',
                    ordering: [[0, "asc"]],
                    stateSave: true,
                    cols :
                        [
                            {
                                data: 'Solicitor',
                                name: 'Solicitor',
                                render: function(data, type, full, meta)
                                {
                                    return full.Solicitor + '<br />' + full.Location;
                                }
                            },
                            {
                                data: 'AverageCompletion',
                                name: 'AverageCompletion'
                            },
                            {
                                data: 'AgentRating',
                                name: 'AgentRating'
                            },
                            {
                                data: 'LegalFee',
                                name: 'LegalFee',
                                render: function(data, type, full, meta)
                                {
                                    return '<span class="' + typeArray['Branch'] + '">From:</span> ' + full.LegalFee ;
                                }
                            },
                            {
                                data: 'Postcode',
                                name: 'Postcode',
                                render: function(data, type, full, meta)
                                {
                                    var url = isbusiness ? '/my-solicitors/' + full.OfficeId : '/solicitors/office/' + full.OfficeId;
                                    return '<a href="' + url + '"><button class="col-sm-24 success-button">View More</button></a>' ;
                                }
                            },
                        ]
                }];

            dataTables_filter =
                [{
                    input_id: '#filterAverage',
                    col_ref: 1,
                    type: 'numeric',
                    max: '66',
                    dataTableID: '#solicitorTable',
                    stateSave: true
                },
                {
                    input_id: '#filterPostcode',
                    col_ref: 4,
                    type: 'text',
                    dataTableID: '#solicitorTable',
                    stateSave: true,
                },
                {//temp use of this to use as alpha filter until we get code
                    input_id: '#filterRadius',
                    col_ref: 0,
                    type: 'alpha',
                    make_options: true,
                    dataTableID: '#solicitorTable',
                    stateSave: true,
                    search_first: true
                }];

            makeDatatable(datatables_info);
            initalizeSelectBoxItems(dataTables_filter);
        });
	</script>
@endpush
