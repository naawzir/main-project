@extends('layouts.app')
@section('content')

<div class="col-sm-20 window">
<div class="overlay"></div>
    <div class="row">
        <div class="col-sm-24">

            @include('layouts.breadcrumb', [
                "breadcrumbs" =>
                    [
                        'cases'
                    ]
                ]
            )

            <h1>Cases</h1>

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
                            <h4><strong>Cases</strong></h4>
                        </div>

                        <div class="col-sm-12">
                            <div class="datatables-listing-filter" id="filterAccountManagerApplied"><span id="filterAccountManagerRemove" class="filter-remove"> <!--&#9447;--> &#10006; </span>Acc Man</div>
                            <div class="datatables-listing-filter" id="filterStatusApplied"><span id="filterStatusRemove" class="filter-remove"> &#10006; </span>Status</div>
                            <div class="datatables-listing-filter" id="filterAgentApplied"><span id="filterAgentRemove" class="filter-remove"> &#10006; </span>Agent</div>
                            <div class="datatables-listing-filter" id="filterTransactionApplied"><span id="filterTransactionRemove" class="filter-remove"> &#10006; </span>Transaction</div>
                            <div class="datatables-listing-filter" id="filterBranchApplied" ><span id="filterBranchRemove" class="filter-remove"> &#10006; </span><span id="branchName">Branch</span></div>
                            <div class="datatables-listing-filter filter-applied-without-select-element" id="filterDateApplied"><span id="filterDateRemove"> &#10006; </span><span id="date">Date</span></div>
                            <div class="datatables-listing-filter filter-applied-without-select-element" id="filterMyBranchesApplied"><span id="filterMyBranchesRemove"> &#10006; </span><span id="myBranches">My Branches</span></div>
                            <div class="datatables-listing-filter filter-applied-without-select-element" id="filterReferenceApplied"><span id="filterReferenceRemove"> &#10006; </span><span>Reference</span></div>
                            <div class="datatables-listing-filter filter-applied-without-select-element" id="filterMyCasesApplied"><span id="filterMyCasesRemove" class="filter-remove"> &#10006; </span><span id="myCases">My Cases</span></div>
                            <div class="datatables-listing-filter" id="resetFilters"> Reset Filters </div>
                        </div>
                    </div>

                    <div class="panel-body">

                        <table class="table table-bordered" id="casesTable">
                            <thead>
                                <tr>
                                    <th id="date_created">Created</th>
                                    <th id="reference">Reference</th>
                                    <th id="status">Status</th>
                                    <th id="type">Transaction</th>
                                    <th id="address">Address</th>
                                    <th id="solicitor">Solicitor</th>
                                    <th id="accountManager">Account Manager</th>
                                    <th id="agent">Agent</th>
                                    <th id="branch">Branch</th>
                                    <th id="user_id_agent">User Agent ID</th>
                                    <th id="date_created_raw">Created</th>
                                </tr>
                            </thead>
                        </table>

                    </div>
                </div>
            </div>
        </section>

        @include('cases.overview')

    </div>

        @push('scripts')
            @include('cases.datatables')
            <script type='text/javascript' src="/js/case/case-listing.js"></script>
        @endpush

    @endsection
