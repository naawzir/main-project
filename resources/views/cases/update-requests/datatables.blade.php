<script type="text/javascript">

    $(document).ready(function() {

        let caseIdreq = '';

        @if(isset($_GET['clear']))
            sessionStorage.clear();
        @endif

        $('.overlay').show();


        var url = '/cases/update-requests-records';

        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth()+1; //January is 0!
        var yyyy = today.getFullYear();

        var datatables_info =
            [{
                url: url,
                dataTableID: '#AgentUpdateRequestTable',
                ordering: [[0, "asc"]],
                stateSave: true,
                deferLoading: 1,
                lengthmenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                displaylength: 10,
                dom: `l<r>Bt<i<"AgentUpdateRequestTable-page-button"p>>`,
                cols:
                    [
                        /*{
                        data: 'date_created_raw',
                        name: 'date_created_raw',
                        render: function (data, type, full, meta) {
                            return full.date_created;
                        }
                    },*/
                    {
                        data: 'account_manager_user_id',
                        name: 'account_manager_user_id',
                        render: function (data, type, full, meta) {
                            return full.account_manager_name;
                        }
                    },
                    {
                        data: 'agent_id',
                        name: 'agent_id',
                        render: function (data, type, full, meta) {
                            return full.agent_name;
                        }
                    },
                    {
                        data: 'reference',
                        name: 'reference'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'transaction',
                        name: 'transaction'
                    },
                    {
                        data: 'TransactionAddress',
                        name: 'TransactionAddress'
                    },
                    {
                        data: 'Solicitor',
                        name: 'Solicitor'
                    },
                    {
                        data: 'update_request_date',
                        name: 'update_request_date'
                    }
                   /* {
                        data: 'agency_id',
                        name: 'agency_id',
                        render: function (data, type, full, meta) {
                            return full.Agency;
                        }
                    },
                    
                    {
                        data: 'branch_id',
                        name: 'branch_id'
                    }*/]
                }];

        var dataTables_filter =
            [
                {
                    input_id: '#filterAccountManager',
                    col_ref: 0,
                    type: 'default',
                    make_options: false,
                    dataTableID: '#AgentUpdateRequestTable',
                    stateSave: true
                },
                {
                    input_id: '#filterStatus',
                    col_ref: 3,
                    type: 'default',
                    make_options: false,
                    dataTableID: '#AgentUpdateRequestTable',
                    stateSave: true
                },
                {
                    input_id: '#filterAgent',
                    col_ref: 1,
                    type: 'default',
                    make_options: false,
                    dataTableID: '#AgentUpdateRequestTable',
                    stateSave: true
                },
                {
                    input_id: '#filterTransaction',
                    col_ref: 4,
                    type: 'default',
                    make_options: false,
                    dataTableID: '#AgentUpdateRequestTable',
                    stateSave: true
                },
                {
                    input_id: '#filterBranch',
                    col_ref: 8,
                    type: 'default',
                    make_options: false,
                    dataTableID: '#AgentUpdateRequestTable',
                    stateSave: true
                }
            ];

        makeDatatable(datatables_info);

        initalizeSelectBoxItems(dataTables_filter);

        var table = $('#AgentUpdateRequestTable').DataTable();

        var reference = sessionStorage.getItem("reference_value");

        if(reference) {

            table
                .column(1)
                .search(reference);

            $("#filterReferenceApplied").show();

        } else {

            table
                .column(1)
                .search('');

            $("#filterReferenceApplied").hide();

        }

        $("#filterReferenceRemove").click(function() {

            var reference = sessionStorage.getItem("reference_value");

            if(reference) {

                sessionStorage.removeItem("reference_value");

                table
                    .column(1)
                    .search('')
                    .draw();

                $("#filterReferenceApplied").hide();

            }

            var count = 0;

            $(".listing-select").each(function() {

                if($(this).val() != '') {

                    count++;

                }

            });

            var countFiltersAppliedWithoutSelectElement = $(".filter-applied-without-select-element:visible").length;

            var totalCount = count + countFiltersAppliedWithoutSelectElement;

            if(!totalCount) {

                $("#resetFilters").hide();

            }

        });


        @if(isset($_GET['account-manager']))

            var accountManager = "{{ $_GET['account-manager'] }}";

            showCorrectTableRecordsGET('accountManager', accountManager, 'filterAccountManager', 6);

        @elseif(isset($_GET['branch-performance']))

            sessionStorage.setItem("branchPerformance", true);

        @endif

        var resetButtonClicked = sessionStorage.getItem("resetButtonClicked");

        $("#filterAccountManager").change(function() {

            if($(this).val()) {

                sessionStorage.setItem("filterAccountManager_value", $(this).val());

                if(resetButtonClicked) {

                    sessionStorage.removeItem("resetButtonClicked");

                }

            } else {

                sessionStorage.removeItem("filterAccountManager_value");

            }

        });

        var branchPerformance = sessionStorage.getItem("branchPerformance");

        var accountManager = sessionStorage.getItem("filterAccountManager_value");

        if(branchPerformance && !accountManager) {

            $("#filterAccountManager").val('');

            table
                .column(6)
                .search('');

        } else if(resetButtonClicked) {

            $("#filterAccountManager").val('');

            table
                .column(6)
                .search('');

        }
/*        else if(!branchPerformance && !accountManager && !resetButtonClicked) {

            alert("c");

            $("#filterAccountManager").val({{ Auth::id() }});

            sessionStorage.setItem("filterAccountManager_value", $("#filterAccountManager").val());

            table
                .column(6)
                .search({{ Auth::id() }});

        }*/

        $("#filterAccountManagerRemove").click(function() {

            sessionStorage.removeItem("accountManager_value");

        });


        function showCorrectTableRecordsGET(field, val, filter, columnNumber) {

            $("#" + filter).val(val);

            if(field == 'status') {

                if(val == 'instructed_unpanelled') {

                    val = 'Instructed Unpanelled';

                }

            }

            sessionStorage.setItem(filter + "_value", val);

            table
                .column(columnNumber)
                .search( val ? '^'+val+'$' : '', true, false);

            $("#" + filter + "Applied").show();

        }

        function showCorrectTableRecords(val, filter) {

            if(val) {

                $("#" + filter + "Applied").show();

            } else {

                $("#" + filter + "Applied").hide();

            }

        }

        @if(isset($_GET['status']))

            var statusValue = "{{ $_GET['status'] }}";

            showCorrectTableRecordsGET('status', statusValue, 'filterStatus', 2);

        @else

            var status = sessionStorage.getItem("filterStatus_value");

            showCorrectTableRecords(status, 'filterStatus');

        @endif



        @if(isset($_GET['transaction']))

            var transaction = "{{ $_GET['transaction'] }}";

            showCorrectTableRecordsGET('transaction', transaction, 'filterTransaction', 3);

        @else

            var transaction = sessionStorage.getItem("filterTransaction_value");

            showCorrectTableRecords(transaction, 'filterTransaction');

        @endif


        @if(isset($_GET['agent-id']))

            var agentIdValue = "{{ $_GET['agent-id'] }}";

            showCorrectTableRecordsGET('agent', agentIdValue, 'filterAgent', 7);

        @else

            var agentIdValue = sessionStorage.getItem("filterAgent_value");

            showCorrectTableRecords(agentIdValue, 'filterAgent');

        @endif


        @if(isset($_GET['branch-id']))

            var branchId = "{{ $_GET['branch-id'] }}";

            showCorrectTableRecordsGET('branch', branchId, 'filterBranch', 8);

        @else

            var branchId = sessionStorage.getItem("filterBranch_value");

            showCorrectTableRecords(branchId, 'filterBranch');

        @endif


        $("#filterMyBranchesRemove").click(function() {

            removeAppliedFilterWithoutSelectElement('filterMyBranchesApplied', "myBranches");

        });

        var branchId = $("#filterBranch").val();

        if(branchId) {

            var branchName = $("#filterBranch option:selected").attr('data-id');

        }

        table.columns([0, 1]).visible(false);

        table.on('draw', function() {

            $('.overlay').hide();

        });

        table.on('preDraw', function() {

            $('.overlay').show();

        });

        /**
         * This is to remove the following applied filters and reset the relevant select elements to nothing:
         * View/Account Manager
         * Status
         * Agent
         * Transaction
         * Branch (this is a hidden select element)
         */
        $(".filter-remove").click(function() {

            var id = $(this).attr("id");

            var element = id.substring(0, id.indexOf("Remove"));

            $("#" + element).val('').trigger('change');

            $("#" + element + "Applied").hide();

            sessionStorage.removeItem(element + "_value");

        });


        $("#resetFilters").click(function() {

            // only trigger the change for select elements which do have a selected option
            $(".listing-select").each(function() {

                if($(this).val() != '') {

                    $(this).val('');

                    var columnNumber = $(this).attr("data-id");

                    table
                        .column(columnNumber)
                        .search('');

                }

            });

            $(".datatables-listing-filter").hide();

            var sessionVariables = [];
            sessionVariables.push('branchPerformance');
            sessionVariables.push('filterAccountManager_value');
            sessionVariables.push('reference_value');
            sessionVariables.push('filterBranch_value');
            sessionVariables.push('filterTransaction_value');
            sessionVariables.push('filterAgent_value');
            sessionVariables.push('filterStatus_value');

            var count = 0;

            for (var i = 0; i < sessionVariables.length; i++) {

                var session = sessionStorage.getItem(sessionVariables[i]);

                if (session) {

                    count++;

                    sessionStorage.removeItem(sessionVariables[i]);

                }

            }

            if(count > 0) {

                table.draw();

            }

            var sessionVariables = [];
            sessionVariables.push('myBranches');
            sessionVariables.push('dateFrom');

            var count = 0;

            for (var b = 0; b < sessionVariables.length; b++) {

                var session = sessionStorage.getItem(sessionVariables[b]);

                if (session) {

                    count++;

                    sessionStorage.removeItem(sessionVariables[b]);

                }

            }

            sessionStorage.setItem("resetButtonClicked", true);

            if(count > 0) {

                location.reload();

            }

        });


        $(".listing-select").change(function() {

            var id = $(this).attr("id");

            changeFilter(id);

        });


        $(".listing-select").each(function() {

            var id = $(this).attr("id");

            changeFilter(id);

        });


        function changeFilter(id) {

            var value = $("#" + id).val();

            if(value) {

                $("#" + id + "Applied").show();

                $("#resetFilters").show();

                if(id == 'filterBranch') {

                    $("#branchName").text(branchName);

                }

            } else {

                $("#" + id + "Applied").hide();

            }

            var count = 0;

            $(".listing-select").each(function() {

                if($(this).val()) {

                    count++;

                }

            });

            var filterDateApplied = $("#filterDateApplied:visible").length;
            var filterMyBranchesApplied = $("#filterMyBranchesApplied:visible").length;
            var filterReferenceApplied = $("#filterReferenceApplied:visible").length;

            if(!count && !filterDateApplied && !filterMyBranchesApplied && !filterReferenceApplied) {

                $("#resetFilters").hide();

            }

        }

        table.draw();


        @if(!empty($_GET))

            window.history.replaceState({}, "", "/cases/");

        @endif


        $('#AgentUpdateRequestTable').on('click', 'td', function() {

            var target = $(this).closest('tr');

            var rowData = table.row(this).data();

            minimisePanels();

            highlightRow(target);

            populateOverview(rowData);

            caseIdreq = rowData.id;

        });

        $('#close-button').on('click', function(e) {

            e.preventDefault();

            restorePanels();

        });

    });

</script>