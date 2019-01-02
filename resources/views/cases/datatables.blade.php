<script type="text/javascript">

    $(document).ready(function() {
        let caseIdreq = '';

        @if(isset($_GET['clear']))
            sessionStorage.clear();
        @endif

        $('.overlay').show();

        // this function will return a date in this format DD/MM/YYYY
        function dateFormatter(timestamp) {
            var a = new Date(timestamp * 1000);
            var months = ['01','02','03','04','05','06','07','08','09','10','11','12'];
            var year = a.getFullYear().toString();
            var month = months[a.getMonth()];
            var date = ("0" + a.getDate()).slice(-2);
            var formattedDate = date + '/' + month + '/' + year;
            return formattedDate;
        }

        @if(isset($_GET['date']))
            var dateFrom = "{{ $_GET['date'] }}";
            sessionStorage.setItem("dateFrom", dateFrom);
            var formattedDate = dateFormatter(dateFrom);
            $("#date").text(formattedDate);
            $("#filterDateApplied").show();
        @else
            var dateFrom = sessionStorage.getItem("dateFrom");
            if(dateFrom) {
                var formattedDate = dateFormatter(dateFrom);
                $("#date").text(formattedDate);
                $("#filterDateApplied").show();
            } else {
                $("#filterDateApplied").hide();
            }
        @endif

        var url = '/cases/cases-records?date=' + dateFrom;

        // this is for the Date & My Branches applied filters
        function removeAppliedFilterWithoutSelectElement(
            element,
            sessionVariable
        ) {
            $("#" + element).remove();
            var sessionValue = sessionStorage.getItem(sessionVariable);
            if(sessionValue) {
                sessionStorage.removeItem(sessionVariable);
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
            location.reload();
        }

        $("#filterDateRemove").click(function() {
            removeAppliedFilterWithoutSelectElement('filterDateApplied', "dateFrom");
        });

        @if(isset($_GET['my-branches']))
            $("#filterMyBranchesApplied").show();
            sessionStorage.setItem("myBranches", true);
            url += '&my-branches=true';
        @else
            var myBranches = sessionStorage.getItem("myBranches");
            if(myBranches) {
                $("#filterMyBranchesApplied").show();
                url += '&my-branches=true';
            } else {
                $("#filterMyBranchesApplied").hide();
            }
        @endif

        @if(isset($_GET['reference']))
            var reference = "{{ $_GET['reference'] }}";
            sessionStorage.setItem("reference_value", reference);
        @endif

        @if(isset($_GET['user-id-agent']))
            var userIdAgent = "{{ $_GET['user-id-agent'] }}";
            sessionStorage.setItem("userIdAgent_value", userIdAgent);
            $("#filterMyCasesApplied").show();
        @endif

        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth()+1; //January is 0!
        var yyyy = today.getFullYear();

        var datatables_info =
            [{
                url: url,
                dataTableID: '#casesTable',
                ordering: [[0, "desc"]],
                stateSave: true,
                deferLoading: 1,
                lengthmenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                displaylength: 10,
                dom: `l<r>Bt<i<"casesTable-page-button"p>>`,
                cols:
                    [{
                        data: 'date_created_raw',
                        name: 'date_created_raw',
                        render: function (data, type, full, meta) {
                            return full.date_created;
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
                        data: 'account_manager_user_id',
                        name: 'account_manager_user_id',
                        render: function (data, type, full, meta) {
                            return full.account_manager_name;
                        }
                    },
                    {
                        data: 'agency_id',
                        name: 'agency_id',
                        render: function (data, type, full, meta) {
                            return full.Agency;
                        }
                    },
                    {
                        data: 'branch_id',
                        name: 'branch_id'
                    },
                    {
                        data: 'agent_user_id',
                        name: 'agent_user_id'
                    },
                    {
                        data: 'date_created_raw',
                        name: 'date_created_raw'
                    }],
                    buttons: [{
                        //extend: 'excel',
                        titleAttr: 'Case Export',
                        text: 'Export To Excel',
                        //filename: 'case-export_' + dd + '_' + mm + '_' + yyyy,
                        //extension: '.xlsx',
                        //title: 'Case Export ' + dd + '/' + mm + '/' + yyyy,
                        //exportOptions: {columns: [0, 1, 2, 3, 4, 5, 6]},
                        action: function(event, dt, button, config) {
                            var agentId = $('#filterAgent').val();
                            var viewId = $('#filterAccountManager').val();
                            var status = $('#filterStatus').val();
                            var transaction = $('#filterTransaction').val();
                            window.location = '/cases/generate-excel?agent_id=' + agentId + '&view_id='+ viewId + '&status='+ status + '&transaction=' + transaction;
                        }
                    }]
                }];

        var dataTables_filter =
            [
                {
                    input_id: '#filterAccountManager',
                    col_ref: 6,
                    type: 'default',
                    make_options: false,
                    dataTableID: '#casesTable',
                    stateSave: true
                },
                {
                    input_id: '#filterStatus',
                    col_ref: 2,
                    type: 'default',
                    make_options: false,
                    dataTableID: '#casesTable',
                    stateSave: true
                },
                {
                    input_id: '#filterAgent',
                    col_ref: 7,
                    type: 'default',
                    make_options: false,
                    dataTableID: '#casesTable',
                    stateSave: true
                },
                {
                    input_id: '#filterTransaction',
                    col_ref: 3,
                    type: 'default',
                    make_options: false,
                    dataTableID: '#casesTable',
                    stateSave: true
                },
                {
                    input_id: '#filterBranch',
                    col_ref: 8,
                    type: 'default',
                    make_options: false,
                    dataTableID: '#casesTable',
                    stateSave: true
                }
            ];

        makeDatatable(datatables_info);

        initalizeSelectBoxItems(dataTables_filter);

        var table = $('#casesTable').DataTable();

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

        var userIdAgent = sessionStorage.getItem("userIdAgent_value");

        if(userIdAgent) {
            table
                .column(9)
                .search(userIdAgent);

            $("#filterMyCasesRemove").show();
        } else {
            table
                .column(9)
                .search('');

            $("#filterMyCasesApplied").hide();
        }

        $("#filterMyCasesRemove").click(function() {
            var userIdAgent = sessionStorage.getItem("userIdAgent_value");

            if(userIdAgent) {
                sessionStorage.removeItem("userIdAgent_value");
                table
                    .column(9)
                    .search('')
                    .draw();

                $("#filterMyCasesApplied").hide();
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
            if(field == 'status') {
                if(val == 'instructed_unpanelled') {
                    val = 'Instructed Unpanelled';
                }
            }

            $("#" + filter).val(val);

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

        table.columns([6, 7, 8, 9, 10]).visible(false);
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
            sessionVariables.push('userIdAgent_value');

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
            var filterMyCasesApplied = $("#filterMyCasesApplied:visible").length;

            if(
                !count &&
                !filterDateApplied &&
                !filterMyBranchesApplied &&
                !filterReferenceApplied &&
                !filterMyCasesApplied
            ) {
                $("#resetFilters").hide();
            }
        }

        table.draw();

        @if(!empty($_GET))
            window.history.replaceState({}, "", "/cases/");
        @endif

        $('#casesTable').on('click', 'td', function() {
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

        @if(Auth::user()->user_role_id === 9)
            $(".dt-buttons").hide();
        @endif
    });
</script>