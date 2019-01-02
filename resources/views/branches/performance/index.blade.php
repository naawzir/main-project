@extends('layouts.app')

@section('content')

    <div class="col-sm-20 window">

        @include('layouts.breadcrumb', [
            'breadcrumbs' =>
                [
                    'branch',
                ]
            ]
        )

        <div id="page-header" class="col-sm-23">
            <h1>Branch Performance</h1>
        </div>

        <hr/>

        <div id="content-area">
            <div class="panel col-sm-23">
                <div class="panel-body">
                    <h5><strong><span id="branchName">All branches</span></strong></h5>
                    <div class="row" id="branchPerformance">
                        <div class="col-sm-5">
                            <h6><i><span id="showingFrom">Showing MTD (default)</span></i></h6>
                        </div>

                        <div class="col-sm-6">
                            <label class="listing-label">Branch:</label>
                            <select class="listing-select branch-performance-filter" id="branchesFilter">
                                <option value="">View All</option>
                                @foreach($branches as $branch)
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
                </div>
            </div>

            <div class="panel col-sm-23">

                <div class="row">

                    <div class="col-sm-4">
                        <div class="notification-circle primary kpi @if($kpis->prospect === 0)kpi-empty @endif" id="prospect">
                            <p>
                                <span id="prospectsCount">{{ $kpis->prospect }}</span>
                                <br>Prospects
                            </p>
                        </div>
                    </div>

                    <div class="col-sm-1"></div>

                    <div class="col-sm-4">
                        <div class="notification-circle instructions-kpi kpi @if($kpis->instructed === 0)kpi-empty @endif" id="instructed">
                            <p>
                                <span id="instructionsCount">{{ $kpis->instructed }}</span>
                                <br>Instructions
                            </p>
                        </div>
                    </div>

                    <div class="col-sm-1"></div>

                    <div class="col-sm-4">
                        <div class="notification-circle darker-grey kpi @if($kpis->instructed_unpanelled === 0)kpi-empty @endif" id="instructed_unpanelled">
                            <p>
                                <span id="unpanelledInstructionsCount">{{ $kpis->instructed_unpanelled }}</span>
                                <br>Unpanelled Instructions
                            </p>
                        </div>
                    </div>

                    <div class="col-sm-1"></div>

                    <div class="col-sm-4">
                        <div class="notification-circle success kpi @if($kpis->completed === 0)kpi-empty @endif" id="completed">
                            <p>
                                <span id="completionsCount">{{ $kpis->completed }}</span>
                                <br>Completions
                            </p>
                        </div>
                    </div>

                    <div class="col-sm-1"></div>

                    <div class="col-sm-4">
                        <div class="notification-circle warning kpi @if($kpis->aborted === 0)kpi-empty @endif" id="aborted">
                            <p>
                                <span id="abortedCount">{{ $kpis->aborted }}</span>
                                <br>Aborted
                            </p>
                        </div>
                    </div>
                </div>
            </div>

    @include('branches.performance.branches-list')
        </div>
    </div>
    @endsection

    @push('scripts')

        @include('branches.performance.datatables')
        {{--@include('branches.performance.kpis-js')--}}
        @include('branches.performance.branch.branch-kpis-js')

    @endpush
