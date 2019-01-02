<script type="text/javascript">

    $(document).ready(function() {

        $('.overlay').show();

        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth()+1; //January is 0!
        var yyyy = today.getFullYear();

        var url = '/branch/branches-records';

        var datatables_info =
        [{
            url: url,
            dataTableID: '#branchesTable',
            ordering: [[0, "asc"]],
            stateSave: false,
            dom: `tl`,
            lengthmenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            displaylength: -1,
            dom: `l<r>Bt<i<"branchesTable-page-button"p>>`,
            deferLoading: 1,
            cols :
                [
                    {
                        data: 'branch_name',
                        name: 'branch_name'
                    },
                    {
                        data: 'target',
                        name: 'target'
                    },
                    {
                        data: 'achieved_to_date',
                        name: 'achieved_to_date'
                    },
                    {
                        data: 'daily_target',
                        name: 'daily_target'
                    },
                    {
                        data: 'actual_daily_run_rate',
                        name: 'actual_daily_run_rate'
                    },
                    {
                        data: 'predicted_finish',
                        name: 'predicted_finish'
                    },
                    {
                        data: 'risk',
                        name: 'risk',
                        render: function(data, type, full, meta)
                        {
                            var riskType = data;
                            if(riskType < '0') {
                                riskType = '<span class="riskWarning">' + data + '</span>';
                            } else {
                                riskType = '<span class="riskSuccess">' + data + '</span>';
                            }

                            return riskType;
                        }
                    },
                    {
                        data: 'last_contact_date',
                        name: 'last_contact_date'
                    },
                    {
                    data: 'branch_id',
                    name: 'branch_id',
                    render: function(data, type, full, meta)
                    {
                        var url = '/branch/' + full.branch_id;
                        return '<a href="' + url + '"><button class="success-button col-sm-24">View</button></a>';
                    }
                    },
                    {
                        data: 'branch_id',
                        name: 'branch_id'
                    }
                ],
            buttons: [{
                extend: 'excel',
                titleAttr: 'Branches Export',
                text: 'Download Report',
                filename: 'branches-export_' + dd + '_' + mm + '_' + yyyy,
                extension: '.xlsx',
                title: 'Branches Export ' + dd + '/' + mm + '/' + yyyy,
                exportOptions: {columns: [0, 1, 2, 3, 4, 5, 6, 7]}
            }]
        }];

        makeDatatable(datatables_info);

        var dataTables_filter =
        [
            {
                input_id: '#branchesFilter',
                col_ref: 9,
                type: 'default',
                make_options: false,
                dataTableID: '#branchesTable',
                stateSave: false
            }
        ];

        initalizeSelectBoxItems(dataTables_filter);

        var table = $('#branchesTable').DataTable();

        table.columns([9]).visible(false);

        table.on('draw', function() {

            $('.overlay').hide();

        });

        table.on('preDraw', function() {

            $('.overlay').show();

        });

        table.draw();


        $('#branchesTable').on('click', 'td', function() {

            var target = $(this).closest('tr');

            var rowData = table.row(this).data();

            highlightRow(target);

        });

        var month = '{{ strtotime('midnight first day of this month') }}';
        var url = '/branch/monthly-targets-records?month=' + month;

        var datatables_info =
            [{
                url: url,
                dataTableID: '#currentMonthTargetsTable',
                ordering: [[0, "asc"]],
                stateSave: false,
                displaylength: -1,
                deferLoading: 1,
                dom: 'tir',
                cols :
                    [
                        {
                            data: 'branch_name',
                            name: 'branch_name'
                        },
                        {
                            render: function (data, type, full, meta) {
                                if (full.agency_branches_target_id) {

                                    return '<input data-id="' + full.agency_branches_target_id + '" data-field="target" data-table="TargetsAgencyBranch" type="text" value="' + full.target + '" placeholder="Enter target" class="input_editable" />';

                                } else {

                                    return '<input type="text" value="" placeholder="Enter target" class="input-new-target" id="' + full.agency_id + '_' + full.branch_id + '" />';

                                }
                            }
                        },
                        {
                            data: 'account_manager_user_id',
                            name: 'account_manager_user_id',
                            render: function (data, type, full, meta) {
                                return full.account_manager_name;
                            }
                        },
                        {
                            render: function (data, type, full, meta) {
                                if (full.agency_branches_target_id) {

                                    //return '<button class="success-button hide_set_target_btn col-sm-24">Hide</button>';
                                    return '<p class="target_set">Set</p>';
                                } else {

                                    return null;

                                }
                            }
                        },
                        {
                            data: 'agency_id',
                            name: 'agency_id'
                        }
                    ]
            }];

        makeDatatable(datatables_info);

        var dataTables_filter =
            [
                {
                    input_id: '#filterAccountManagerCurrentMonth',
                    col_ref: 2,
                    type: 'default',
                    make_options: false,
                    dataTableID: '#currentMonthTargetsTable',
                    stateSave: false
                },
                {
                    input_id: '#filterAgentCurrentMonth',
                    col_ref: 4,
                    type: 'default',
                    make_options: false,
                    dataTableID: '#currentMonthTargetsTable',
                    stateSave: false
                }
            ];

        initalizeSelectBoxItems(dataTables_filter);

        var table = $('#currentMonthTargetsTable').DataTable();

        table.on('draw', function() {

            $('.overlay').hide();

        });

        table.on('preDraw', function() {

            $('.overlay').show();

        });

        table.columns([4]).visible(false);

        table.draw();

        let originalValue = '';

        $('#currentMonthTargetsTable, #nextMonthTargetsTable').on('focus', 'td .input_editable', function() {

            var element = $(this);

            originalValue = element.val();

        });

        $('#currentMonthTargetsTable, #nextMonthTargetsTable').on('blur', 'td .input_editable', function() {

            var element = $(this);

            updateTable(element, originalValue);

        });

        function updateTable(element, originalValue)
        {
            const value = element.val();
            const table = element.attr("data-table");
            const field = element.attr("data-field");
            const id = element.attr("data-id");

            if(isNaN(value)) {

                alert("The target entered must be a number");
                element.val(originalValue);
                return false;

            }

            if(value != originalValue) {

                const data = {
                    field : field,
                    table : table,
                    value : value,
                    id : id
                };

                const ajaxRequest = tcp.xhr.post('/global/update', data);

                ajaxRequest.done((responseData) => {
                    $(element).val(responseData.value);
                    element.closest('tr').find("td:last").html('<p class="target_set">Set<span class="target_updated"> &#10004;</span></p>');
                    setTimeout(function()
                        {
                            $(".target_updated").fadeOut();
                        }, 2000
                    );
                });

                ajaxRequest.fail(() => {
                    alert("failed");
                });

            }

            return false;

        }

        $('#currentMonthTargetsTable, #nextMonthTargetsTable').on('keypress', 'td .input_editable', function(e) {

            if(e.which === 13) { // pressed enter

                var element = $(this);

                updateTable(element, originalValue);

            }

        });

        function hideBranchTargetRow(tableInfoId, table, buttons, element)
        {
            table.row(element.parent().parent().hide());
            var info = table.page.info();

            var recordsTotal = $("#recordsTotal").text();
            var recordsTotalFixed = info.recordsTotal;

            if(!recordsTotal) {

                var initialRecordsTotal = info.recordsTotal;
                var recordsDisplay = info.recordsDisplay;

                $('#' + tableInfoId).html(

                    '<span>Showing ' + (info.start + 1) + ' of ' + (recordsDisplay - 1) + ' of ' + '<span id="recordsTotal">' + (recordsDisplay - 1) + '</span> entries (filtered from ' + initialRecordsTotal + ' total entries)</span>'

                );

            } else {

                $('#' + tableInfoId).html(

                    '<span>Showing ' + (info.start + 1) + ' of ' + (recordsTotal - 1) + ' of ' + '<span id="recordsTotal">' + (recordsTotal - 1) + '</span> entries (filtered from ' + recordsTotalFixed + ' total entries)</span>'

                );

            }

            var showAllTargetsSetMonth = $("#showAllTargetsSet" + buttons + "Month").is(":visible");
            if(!showAllTargetsSetMonth) {
                $("#showAllTargetsSet" + buttons + "Month").show();
            }

            var hideAllTargetsSetMonth = $("#hideAllTargetsSet" + buttons + "Month").is(":visible");
            if(hideAllTargetsSetMonth) {
                $("#hideAllTargetsSet" + buttons + "Month").hide();
            }
        }

        $('#currentMonthTargetsSection, #nextMonthTargetsSection').on('blur', '.input-new-target', function()
        {
            var element = $(this);

            const month = $("#month").text();

            const target = element.val();

            if(target) {

                const id = element.attr("id");

                const data = {
                    target : target,
                    id : id,
                    month : month
                };

                const ajaxRequest = tcp.xhr.post('/branch/create-target', data);

                ajaxRequest.done((responseData) => {

                    $(element)
                        .attr('id', '')
                        .attr('data-id', responseData.target_id)
                        .attr('data-field', 'target')
                        .attr('data-table', 'TargetsAgencyBranch')
                        .val(target)
                        .removeClass('input-new-target')
                        .addClass('input_editable');

                    $(this).closest('tr').find("td:last").html('<p class="target_set">Set<span class="target_updated"> &#10004;</span></p>');
                    setTimeout(function()
                        {
                            $(".target_updated").fadeOut();
                        }, 2000
                    );
                });

                ajaxRequest.fail(() => {
                    alert("failed");
                });

            }

        });

        //var url = '/branch/getNextMonthTargetsRecords';
        var month = '{{ strtotime('midnight first day of next month') }}';
        var url = '/branch/monthly-targets-records?month=' + month;

        var datatables_info =
            [{
                url: url,
                dataTableID: '#nextMonthTargetsTable',
                ordering: [[0, "asc"]],
                stateSave: false,
                displaylength: -1,
                deferLoading: 1,
                dom: 'tir',
                cols :
                    [
                        {
                            data: 'branch_name',
                            name: 'branch_name'
                        },
                        {
                            render: function (data, type, full, meta) {
                                if (full.agency_branches_target_id) {

                                    return '<input data-id="' + full.agency_branches_target_id + '" data-field="target" data-table="AgencyBranchesTarget" type="text" value="' + full.target + '" placeholder="Enter target" class="input_editable" />';

                                } else {

                                    return '<input type="text" value="" placeholder="Enter target" class="input-new-target" id="' + full.agency_id + '_' + full.branch_id + '" />';

                                }
                            }
                        },
                        {
                            data: 'account_manager_user_id',
                            name: 'account_manager_user_id',
                            render: function (data, type, full, meta) {
                                return full.account_manager_name;
                            }
                        },
                        {
                            render: function (data, type, full, meta) {
                                if (full.agency_branches_target_id) {

                                    //return '<button class="success-button hide_set_target_btn col-sm-24">Hide</button>';
                                    return '<p class="target_set">Set</p>';
                                } else {

                                    return null;

                                }
                            }
                        },
                        {
                            data: 'agency_id',
                            name: 'agency_id'
                        }
                    ]
            }];

        makeDatatable(datatables_info);

        var dataTables_filter =
            [
                {
                    input_id: '#filterAccountManagerNextMonth',
                    col_ref: 2,
                    type: 'default',
                    make_options: false,
                    dataTableID: '#nextMonthTargetsTable',
                    stateSave: false
                },
                {
                    input_id: '#filterAgentNextMonth',
                    col_ref: 4,
                    type: 'default',
                    make_options: false,
                    dataTableID: '#nextMonthTargetsTable',
                    stateSave: false
                }
            ];

        initalizeSelectBoxItems(dataTables_filter);

        var table2 = $('#nextMonthTargetsTable').DataTable();

        table2.on('draw', function() {

            $('.overlay').hide();

        });

        table2.on('preDraw', function() {

            $('.overlay').show();

        });

        table2.columns([4]).visible(false);

        table2.draw();

        function hideAllBranchTargetRows(tableId, table, button, thisElement)
        {
            //var tableRowsToHide = $("#" + tableId).find('.hide_set_target_btn').parent().parent();
            var tableRowsToHide = $("#" + tableId).find('.target_set').parent().parent();
            var countTableRowsToHide = tableRowsToHide.length;
            tableRowsToHide.hide();

            var info = table.page.info();

            var recordsTotal = $("#recordsTotal").text();

            if(!recordsTotal) {

                var initialRecordsTotal = info.recordsTotal;

                $('#' + tableId + '_info').html(

                    '<span>Showing ' + (info.start + 1) + ' of ' + (initialRecordsTotal - countTableRowsToHide) + ' of ' + '<span id="recordsTotal">' + (initialRecordsTotal - countTableRowsToHide) + '</span> entries.</span>'

                );

            } else {

                $('#' + tableId + '_info').html(

                    '<span>Showing ' + (info.start + 1) + ' of ' + (recordsTotal - countTableRowsToHide) + ' of ' + '<span id="recordsTotal">' + (recordsTotal - countTableRowsToHide) + '</span> entries.</span>'

                );

            }

            thisElement.hide();

            $('#showAllTargetsSet' + button + 'Month').show();
        }

        $("#hideAllTargetsSetNextMonth").click(function() {

            hideAllBranchTargetRows('nextMonthTargetsTable', table2, 'Next', $(this));

        });

        $("#hideAllTargetsSetCurrentMonth").click(function() {

            hideAllBranchTargetRows('currentMonthTargetsTable', table, 'Current', $(this));

        });

        function showAllBranchTargetRows(tableId, table, button, thisElement)
        {
            var countTableRowsToShow = $("#" + tableId + " tr:hidden").length;

            $("#" + tableId + " tr:hidden").show();

            var info = table.page.info();

            var recordsTotal = $("#recordsTotal").text();

            if(!recordsTotal) {

                var initialRecordsTotal = info.recordsTotal;

                $('#' + tableId + '_info').html(

                    '<span>Showing ' + (info.start + 1) + ' of ' + (+initialRecordsTotal + +countTableRowsToShow) + ' of ' + '<span id="recordsTotal">' + (+initialRecordsTotal + +countTableRowsToShow) + '</span> entries.</span>'

                );

            } else {

                $('#' + tableId + '_info').html(

                    '<span>Showing ' + (info.start + 1) + ' of ' + (+recordsTotal + +countTableRowsToShow) + ' of ' + '<span id="recordsTotal">' + (+recordsTotal + +countTableRowsToShow) + '</span> entries.</span>'

                );

            }

            thisElement.hide();

            $('#hideAllTargetsSet' + button + 'Month').show();
        }

        $("#showAllTargetsSetCurrentMonth").click(function() {

            showAllBranchTargetRows('currentMonthTargetsTable', table, 'Current', $(this));

        });

        $("#showAllTargetsSetNextMonth").click(function() {

            showAllBranchTargetRows('nextMonthTargetsTable', table2, 'Next', $(this));

        });

        $("#nextMonthTargetsBtn").click(function() {

            $(this).hide();

            $("#currentMonthTargetsBtn").show();

            $("#currentMonthTargetsSection").fadeOut();

            $("#nextMonthTargetsSection").fadeIn();

            $("#month").text({{ strtotime('midnight first day of next month') }});

            $("#currentMonthTableFilters").fadeOut();

            $("#nextMonthTableFilters").fadeIn();

        });

        $("#currentMonthTargetsBtn").click(function() {

            $(this).hide();

            $("#nextMonthTargetsBtn").show();

            $("#nextMonthTargetsSection").fadeOut();

            $("#currentMonthTargetsSection").fadeIn();

            $("#month").text({{ strtotime('midnight first day of this month') }});

            $("#nextMonthTableFilters").fadeOut();

            $("#currentMonthTableFilters").fadeIn();

        });

        $("#setMonthlyTargetsBtn").click(function() {

            $("#setMonthlyTargetsBtnSection").fadeOut();

            $("#branchesList").fadeOut();

            $("#currentMonthTargetsSection").fadeIn();

            $("#showBranchesBtnSection").fadeIn();

            $("#nextMonthTargetsBtn").fadeIn();

            $("#currentMonthTableFilters").fadeIn();

        });

        $("#showBranchesBtn").click(function() {

            location.reload();

        });

    });

</script>
