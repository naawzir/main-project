@extends('layouts.app')
@section('content')

    <div class="col-sm-20 window">
        @include('layouts.breadcrumb', ['breadcrumbs' => [
                'branch' => 'Branch',
                'performance' => 'Performance',
                $branch->id => $branch->name]
            ])
        <div class="row">
            <div class="col-sm-24">
                <h1>{{ $agency->name . ', ' . $branch->name }}</h1>
            </div>
        </div>

        <p id="agencyId" class="hidden">{{ $agency->id }}</p>
        <p id="branchId" class="hidden">{{ $branch->id }}</p>

        <hr>

        <div id="content-area">
            <div class="panel col-sm-23">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <h5><strong>Performance</strong></h5>
                            <h6><i><span id="showingFrom">Showing MTD (default)</span></i></h6>
                        </div>

                        <div class="col-sm-10">
                            <div class="row">
                                @include('partials._date-filters')
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <button class="success-button col-sm-22" id="resetFiltersBtn" float="left">Reset</button>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3">
                            <div class="notification-circle primary kpi" id="prospect">
                                <p>
                                    <span id="prospectsCount">{{ $kpis->prospect }}</span>
                                    <br>Prospects
                                </p>
                            </div>
                        </div>

                        <div class="col-sm-1"></div>

                        <div class="col-sm-3">
                            <div class="notification-circle instructions-kpi kpi" id="instructed">
                                <p>
                                    <span id="instructionsCount">{{ $kpis->instructed }}</span>
                                    <br>Instructions
                                </p>
                            </div>
                        </div>

                        <div class="col-sm-1"></div>

                        <div class="col-sm-3">
                            <div class="col-sm-12">
                                @svg('donut-chart', ['id' => 'conversion'])
                            </div>
                        </div>

                        <div class="col-sm-1"></div>

                        <div class="col-sm-3">
                            <div class="notification-circle darker-grey kpi" id="instructed_unpanelled">
                                <p>
                                    <span id="unpanelledInstructionsCount">{{ $kpis->instructed_unpanelled }}</span>
                                    <br>Unpanelled Instructions
                                </p>
                            </div>
                        </div>

                        <div class="col-sm-1"></div>

                        <div class="col-sm-3">
                            <div class="notification-circle success kpi" id="completed">
                                <p>
                                    <span id="completionsCount">{{ $kpis->completed }}</span>
                                    <br>Completions
                                </p>
                            </div>
                        </div>

                        <div class="col-sm-1"></div>

                        <div class="col-sm-3">
                            <div class="notification-circle warning kpi" id="aborted">
                                <p>
                                    <span id="abortedCount">{{ $kpis->aborted }}</span>
                                    <br>Aborted
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="targetsSection" class="panel col-sm-23">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-21">
                            <h5><strong>Targets</strong></h5>
                        </div>

                        <div class="col-sm-3">
                            <button class="success-button col-sm-22" id="editKPIBtn">Edit KPI</button>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6 row">
                        <div class="col-sm-24 ml-4">
                            <p><strong>This month</strong></p>
                        </div>

                        <div class="col-sm-12">
                            @svg('donut-chart', ['id' => 'branchMonthlyTarget'])
                        </div>

                        <div class="col-sm-12">
                            @svg('donut-chart', ['id' => 'branchInstructionsMTD'])
                        </div>
                    </div>

                    <div class="col-sm-6 row left-col">
                        <div class="col-sm-24 ml-4">
                            <p><strong>Daily</strong></p>
                        </div>

                        <div class="col-sm-12">
                            @svg('donut-chart', ['id' => 'branchDailyTarget'])
                        </div>

                        <div class="col-sm-12">
                            @svg('donut-chart', ['id' => 'branchActualDailyRunRate'])
                        </div>
                    </div>

                    <div class="col-sm-6 row left-col">
                        <div class="col-sm-24 ml-4">
                            <p><strong>Predicted Finish</strong></p>
                        </div>

                        <div class="col-sm-12">
                            <div class="notification-circle warning">
                                <p>
                                    <span id="riskCount">{{ $branchTargetsKPIs->risk }}</span>
                                    <br>Risk
                                </p>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="notification-circle warning">
                                <p>
                                    <span id="caseLeadsCount">{{ $branchTargetsKPIs->predicted_finish }}</span>
                                    <br>Cases Instructed
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

                <div id="KpiSection" class="panel col-sm-23 hidden">
                    <div class="row">
                        <div class="col-sm-20">
                            <h5><strong>KPI</strong></h5>
                        </div>

                        <div class="col-sm-4">
                            <div class="row">
                                <div class="col-sm-12">
                                    <button class="col-sm-22 cancel-button" id="KPICancelBtn"><a href="#">Cancel</a></button>
                                </div>

                                <div class="col-sm-12">
                                    <button class="col-sm-22 success-button" id="KPISaveBtn"><a href="#">Save</a></button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-8">
                            <label><strong>Set Target</strong></label>
                            <p>
                                <input id="editKPITarget" type="text" name="target" value="{{ $branchTargetsKPIs->target }}" placeholder="Target">
                            </p>
                        </div>

                        <p id="achievedToDate" class="hidden">{{ $branchTargetsKPIs->achieved_to_date }}</p>

                        <div class="col-sm-8">
                            <div>

                                <p><span class="col-sm-12" style="display:inline-block;">Daily Target: </span><span id="editKPIDailyTarget">{{ $branchTargetsKPIs->daily_target }}</span></p>
                                <p><span class="col-sm-12" style="display:inline-block;">Risk: </span><span id="editKPIRisk">{{ $branchTargetsKPIs->risk }}</span></p>
                            </div>
                        </div>

                        <div class="col-sm-8">

                        </div>
                    </div>

                </div>

            @include('branches.performance.branch.contact-notes')
            @include('branches.performance.branch.cases')

            </div>
        </div>

    @endsection

    @push('scripts')

        <script>
            window.jQuery(function($) {
                const branchId = $("#branchId").text();

                $("#addContactNoteBtn").click(function() {
                    $("#branchContactNotesSection").fadeOut();
                    $("#addBranchContactNoteSection").fadeIn();
                });

                $("#addBranchContactNoteCancel").click(function() {
                    $("#addBranchContactNoteSection").fadeOut();
                    $("#branchContactNotesSection").fadeIn();
                });

                $(".create-branch-contact-note").click(function() {
                    const sendEmail = $(this).attr("data-send");
                    const branchContactNote = $("#branchContactNote").val();
                    const branchContactNoteLength = branchContactNote.length;

                    if(branchContactNoteLength === 0) {
                        alert("Please enter a note");
                        return false;
                    } else {
                        const data = {
                            note : branchContactNote,
                            branchId : branchId,
                            sendEmail : sendEmail
                        };

                        const ajaxRequest = tcp.xhr.post('/branch/add-branch-contact-note', data);
                        ajaxRequest.done(function(data) {
                            location.reload();
                        });

                        ajaxRequest.fail(function(data) {
                            alert("failed");
                        });
                    }
                });

                $("#editKPIBtn").click(function() {
                    $("#targetsSection").fadeOut();
                    $("#KpiSection").fadeIn();
                });

                $("#KPICancelBtn").click(function() {
                    $("#KpiSection").fadeOut();
                    $("#targetsSection").fadeIn();
                });

                $("#KPISaveBtn").click(function() {
                    const target = $("#editKPITarget").val();

                    const data = {
                        target : target,
                        branchId : branchId
                    };

                    const ajaxRequest = tcp.xhr.post('/branch/update-kpi-target', data);
                    ajaxRequest.done(function(data) {
                        location.reload();
                    });

                    ajaxRequest.fail(function(data) {
                        alert("failed");
                    });
                });

                $("#editKPITarget").keyup(function() {
                    const target = $(this).val();
                    const achieved_to_date = $("#achievedToDate").text();

                    const data = {
                        target : target,
                        achieved_to_date : achieved_to_date
                    };

                    const ajaxRequest = tcp.xhr.get('/branch/edit-kpi-target', data);

                    ajaxRequest.done(function(data) {
                        $("#editKPIDailyTarget").text(data.daily_target);
                        $("#editKPIRisk").text(data.risk);
                    });

                    ajaxRequest.fail(function(data) {
                        alert("failed");
                    });
                });

                const conversion = @json($dashData['conversion']['prospectToInstructed']);
                const percentSymbol = conversion[0]['percent'];
                fillDonut('#conversion', conversion);
                if(percentSymbol) {
                    $(".chart-number").append('%');
                }

                var branchMonthlyTarget = @json($dashData['branchTargetsKPIs']['branchMonthlyTarget']);
                var branchInstructionsMTD = @json($dashData['branchTargetsKPIs']['branchInstructionsMTD']);
                var branchDailyTarget = @json($dashData['branchTargetsKPIs']['branchDailyTarget']);
                var branchActualDailyRunRate = @json($dashData['branchTargetsKPIs']['branchActualDailyRunRate']);

                fillDonut('#branchMonthlyTarget', branchMonthlyTarget);
                fillDonut('#branchInstructionsMTD', branchInstructionsMTD);
                fillDonut('#branchDailyTarget', branchDailyTarget);
                fillDonut('#branchActualDailyRunRate', branchActualDailyRunRate);

                $('#branchContactTable').on('click', '.view_more', function() {
                    var element = $(this);
                    var noteId = element.next().text();
                    $("#lessNotes_" + noteId).hide();
                    $("#moreNotes_" + noteId).show();
                });

                $('#branchContactTable').on('click', '.view_less', function() {
                    var element = $(this);
                    var noteId = element.next().text();
                    $("#moreNotes_" + noteId).hide();
                    $("#lessNotes_" + noteId).show();
                });
            });

        </script>

        @include('branches.performance.branch.branch-kpis-js')
        @include('branches.performance.branch.datatables')

    @endpush