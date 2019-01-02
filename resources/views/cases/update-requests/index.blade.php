@extends('layouts.app')
@section('content')

<div class="col-sm-20 window">
<div class="overlay"></div>
    <div class="row">
        <div class="col-sm-24">

            @include('layouts.breadcrumb', [
                "breadcrumbs" =>
                    [
                        "Agent Update Requests"
                    ]
                ]
            )

            <h1>Agent Update Requests</h1>
        
        </div>
    </div>

    <div class="row">
        
        <section class="full col-sm-24">
            <div id="content-container" class="row">
                <div id="added-content-area" class="clean-panel col-sm-23">
                    <div class="row">
                        @include('cases.filters')
                    </div>
                </div>
            </div>


            <div class="row">
                <div id="content-area" class="panel col-sm-23">
                    <div class="row">
                        <div class="col-sm-12">
                            <h4><strong>Agent Update Request</strong></h4>
                        </div>

                        <div class="col-sm-12">
                        <div class="datatables-listing-filter" id="filterAccountManagerApplied">
								<span id="filterAccountManagerRemove" class="filter-remove">
									<!--&#9447;--> &#10006; 
						
								</span>Acc Man
					
							</div>
							<div class="datatables-listing-filter" id="filterAgentApplied">
								<span id="filterAgentRemove" class="filter-remove">&#10006; </span>Agent
					
							</div>
							<div class="datatables-listing-filter" id="filterStatusApplied">
								<span id="filterStatusRemove" class="filter-remove"> &#10006; </span>Status
					
							</div>
							<div class="datatables-listing-filter" id="filterTransactionApplied">
								<span id="filterTransactionRemove" class="filter-remove"> &#10006; </span>Transaction
					
							</div>
                        </div>
                    </div>

                    <div class="panel-body">

                        <table class="table table-bordered" id="AgentUpdateRequestTable">
                        <thead>
							<tr>
                                <th>User Staff</th>
                                <th>Agent</th>
								<th id="case_ref">Case Ref</th>
								<th id="status">Status</th>
								<th id="type">Transaction</th>
								<th id="address">Address</th>
								<th id="solicitor">Solicitor</th>
                                <th id="UpdateRequestDate">Update Request Date</th>
							</tr>
						</thead>
                        </table>

                    </div>

                </div>

            </div>

        </section>

        @include('cases.overview')

    </div>
    @endsection

  @push('scripts')
        <script type='text/javascript' src="/js/case/case-listing.js"></script>
        @include('cases.update-requests.datatables')
    @endpush

