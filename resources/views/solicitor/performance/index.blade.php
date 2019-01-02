@extends('layouts.app')
@section('content')
<div class="col-sm-20 window">
    @include('layouts.breadcrumb', [
        "breadcrumbs" =>
            [
                "solicitor",
                "performance"
            ]
        ]
    )
    <div id="page-header" class="col-sm-23 ml-4">
        <div class="row">
            <div class="col-sm-12">
                <h1>Solicitor Performance</h1>
            </div>
            <div class="col-sm-12">
                <a href="/solicitors/create">
                    <button class="success-button col-sm-10 float-right">Add New Solicitor</button>
                </a>
            </div>
        </div>
    </div>
    <hr/>
    <div id="added-content-area">
        <div class="col-sm-23">
            <div class="row">
                <div class="panel col-sm-8">
                    <h5><strong>Current Snapshot</strong></h5>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="notification-circle primary">
                                <p>
                                    <span id="prospectsCount"></span>
                                    <br>Pipeline
                                </p>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            @svg('donut-chart', ['id' => 'branchMonthlyTarget'])
                        </div>
                    </div>
                </div>
                <div class="panel col-sm-16">
                    <h5><strong>Current Performance (MTD)</strong></h5>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="notification-circle instructions-kpi">
                                <p>
                                    <span id="prospectsCount">{{ $kpis->instructed }}</span>
                                    <br>Instructions
                                </p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="notification-circle darker-grey">
                                <p>
                                    <span id="instructionsCount">{{ $kpis->instructed_unpanelled }}</span>
                                    <br>Unpanelled Instructions
                                </p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="notification-circle success">
                                <p>
                                    <span id="prospectsCount">{{ $kpis->completed }}</span>
                                    <br>Completions
                                </p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="notification-circle warning">
                                <p>
                                    <span id="instructionsCount">{{ $kpis->aborted }}</span>
                                    <br>Aborted
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel col-sm-23">
            <h3>Solicitor Panel</h3>
            <div class="panel-body">
                <table class="table table-bordered" id="solicitorsStatsTable">
                    <thead>
                    <tr>
                        <th>Solicitor</th>
                        <th>Pipeline</th>
                        <th>Capacity Remaining</th>
                        <th>Instructions MTD</th>
                        <th>Unpanelled Instructions MTD</th>
                        <th>Completions MTD</th>
                        <th>Aborted MTD</th>
                        <th></th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
    <script type="text/javascript" src="/js/solicitor/performance/datatables.js"></script>
@endpush
