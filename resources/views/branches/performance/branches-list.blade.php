<!-- inserted some code here which checks if the user logged in is the Account Manager Lead
        - this is temporary code which will be replaced -->
        @if(Auth::user()->id === 14860)
            <div class="clean-panel col-sm-23" id="setMonthlyTargetsBtnSection" style="background:none;border:none;box-shadow:none;">
                <div class="row">
                    <div class="col-sm-21"></div>
                    <div class="col-sm-3">
                        <button id="setMonthlyTargetsBtn" class="cancel-button col-sm-24">Set Monthly Targets</button>
                    </div>
                </div>
            </div>

            <div class="clean-panel col-sm-23 hidden" id="showBranchesBtnSection" style="background:none;border:none;box-shadow:none;">
                <div class="row">
                    <div class="col-sm-21"></div>
                    <div class="col-sm-3">
                        <button id="showBranchesBtn" class="success-button col-sm-24">Show Branches</button>
                    </div>
                </div>
            </div>
        @endif

        <div id="branchesList" class="clean-panel col-sm-23">
            <div class="panel-body">
                <table class="table table-bordered" id="branchesTable">
                    <thead>
                    <tr>
                        <th>Branch</th>
                        <th>Target</th>
                        <th>Achieved to Date</th>
                        <th>Daily Target</th>
                        <th>Actual Daily Run Rate</th>
                        <th>Predicted Finish</th>
                        <th>Risk</th>
                        <th>Last Contact</th>
                        <th></th>
                        <th>Branch ID</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
        <!-- end of branches table -->

        <p id="month" class="hidden">{{ strtotime('midnight first day of this month') }}</p>

        <div id="currentMonthTableFilters" class="clean-panel hidden col-sm-23">
            <div class="row">
                <div class="col-sm-12 margin-filter">
                    <label class="listing-label">AM</label>
                    <select class="listing-select" data-id="6" id="filterAccountManagerCurrentMonth">
                        <option value="">Please select</option>
                        @foreach($accountManagers as $accountManager)
                            <option value="{{ $accountManager->id }}">{{ $accountManager->forenames . ' ' . $accountManager->surname }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-sm-12">
                    <label class="listing-label">Agent</label>
                    <select class="listing-select" id="filterAgentCurrentMonth">
                        <option value="">Please select</option>
                        @foreach($agencies as $agency)
                            <option value="{{ $agency->id }}">{{ $agency->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div id="nextMonthTableFilters" class="clean-panel hidden col-sm-23">
            <div class="row">
                <div class="col-sm-12 margin-filter">
                    <label class="listing-label">AM</label>
                    <select class="listing-select" data-id="6" id="filterAccountManagerNextMonth">
                        <option value="">Please select</option>
                        @foreach($accountManagers as $accountManager)
                            <option value="{{ $accountManager->id }}">{{ $accountManager->forenames . ' ' . $accountManager->surname }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-sm-12">
                    <label class="listing-label">Agent</label>
                    <select class="listing-select" id="filterAgentNextMonth">
                        <option value="">Please select</option>
                        @foreach($agencies as $agency)
                            <option value="{{ $agency->id }}">{{ $agency->name }}</option>
                        @endforeach
                    </select>
                </div>

            </div>

        </div>

        <div class="clean-panel col-sm-23" style="background:none;border:none;box-shadow:none;">
            <button id="nextMonthTargetsBtn" data-id="{{ strtotime('midnight first day of the next month') }}" class="cancel-button hidden">Set targets for {{ date("F", strtotime('+1 month')) }}</button>
            <button id="currentMonthTargetsBtn" data-id="{{ strtotime('midnight first day of the this month') }}" class="cancel-button hidden">Set targets for {{ date("F") }}</button>
        </div>

        <div id="currentMonthTargetsSection" class="panel col-sm-23 hidden">
            <div class="row">
                <div class="col-sm-10">
                    <div class="row">
                        <div class="col-sm-8">
                            <h4><strong>Monthly Targets</strong></h4>
                        </div>

                        <div class="col-sm-16">
                            <h4>- current month ({{ date("F") }})</h4>
                        </div>
                    </div>
                </div>

                <div class="col-sm-11"></div>

                <div class="col-sm-3">
                    <button id="hideAllTargetsSetCurrentMonth" class="cancel-button col-sm-24">Hide set targets</button>
                    <button id="showAllTargetsSetCurrentMonth" class="success-button col-sm-24 hidden">Show set targets</button>
                </div>

            </div>

            <div class="panel-body">
                <table class="table table-bordered monthly_targets_table" id="currentMonthTargetsTable">
                    <div class="col-sm-12">
                    </div>

                    <thead>
                        <tr>
                            <th>Agency / Branch</th>
                            <th>Target</th>
                            <th>Account Manager</th>
                            <th>Status</th>
                            <th>Agency</th>
                        </tr>
                    </thead>

                </table>

            </div>

        </div>

        <div id="nextMonthTargetsSection" class="panel col-sm-23 hidden">
            <div class="row">
                <div class="col-sm-10">
                    <div class="row">
                        <div class="col-sm-8">
                            <h4><strong>Monthly Targets</strong></h4>
                        </div>

                        <div class="col-sm-16">
                            <h5>- next month ({{ date("F", strtotime('+1 month')) }})</h5>
                        </div>
                    </div>
                </div>

                <div class="col-sm-11"></div>

                <div class="col-sm-3">
                    <button id="hideAllTargetsSetNextMonth" class="cancel-button col-sm-24">Hide set targets</button>
                    <button id="showAllTargetsSetNextMonth" class="success-button col-sm-24 hidden">Show set targets</button>
                </div>
            </div>

            <div class="panel-body">
                <table class="table table-bordered monthly_targets_table" id="nextMonthTargetsTable">
                    <thead>
                    <tr>

                        <th>Agency / Branch</th>
                        <th>Target</th>
                        <th>Account Manager</th>
                        <th>Status</th>
                        <th>Agency</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>

    </div>

</div>