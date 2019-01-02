<script type="text/javascript">

    $(document).ready(function() {

        $('.overlay').show();

        var branchId = $("#branchId").text();

        var url = '/branch/branch-notes-records/' + branchId;

        var datatables_info =
            [{
                url: url,
                dataTableID: '#branchContactTable',
                ordering: [[0, "desc"]],
                stateSave: false,
                dom: `tl`,
                lengthmenu: [[5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, "All"]],
                displaylength: 5,
                dom: `l<r>Bt<i<"branchContactTable-page-button"p>>`,
                deferLoading: 1,
                cols :
                    [
                        {
                            data: 'date_created_raw',
                            name: 'date_created_raw',
                            render: function (data, type, full, meta) {
                                return full.date_created;
                            }
                        },
                        {
                            data: 'Staff',
                            name: 'Staff'
                        },
                        {
                            data: 'note_content',
                            name: 'note_content',
                            render: function (data, type, full, meta) {

                                var noteOriginal = data;

                                var noteId = full.note_id;

                                if(noteOriginal.length > 200) {

                                    var note = '';
                                    note+= '<div id="lessNotes_' + noteId + '">';
                                    note+= '<span>' + noteOriginal.substring(0, 200) + '...</span>';

                                    note+= '<span class="view_more"> View More</span>';
                                    note+= '<div class="hidden">' + noteId + '</div>';
                                    note+= '</div>';

                                    note+= '<div id="moreNotes_' + noteId + '" class="hidden"><span>' + noteOriginal + '</span>';
                                    note+= '<span>' + noteOriginal + '</span>';

                                    note+= '<span class="view_less"> View Less</span>';
                                    note+= '<div class="hidden">' + noteId + '</div>';
                                    note+= '</div>';

                                } else {

                                    var note = noteOriginal;

                                }

                                return note;

                            }
                        }
                    ]
            }];


        makeDatatable(datatables_info);

        var table = $('#branchContactTable').DataTable();

        table.on('draw', function() {

            $('.overlay').hide();

        });

        table.on('preDraw', function() {

            $('.overlay').show();

        });

        table.draw();


        $('#branchContactTable').on('click', 'td', function() {

            var target = $(this).closest('tr');

            var rowData = table.row(this).data();

            highlightRow(target);

        });



        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth()+1; //January is 0!
        var yyyy = today.getFullYear();

        var branchId = $("#branchId").text();

        var url = '/branch/case-records/' + branchId;

        var datatables_info =
            [{
                url: url,
                dataTableID: '#activeCasesTable',
                ordering: [[0, "desc"]],
                stateSave: true,
                deferLoading: 1,
                //lengthmenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                lengthmenu: [[5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, "All"]],
                displaylength: 5,
                dom: `l<r>Bt<i<"activeCasesTable-page-button"p>>`,
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
                        }
                        /*,
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
                        }*/
                        ]
/*                ,buttons: [{
                    extend: 'excel',
                    titleAttr: 'Case Export',
                    text: 'Export To Excel',
                    filename: 'case-export_' + dd + '_' + mm + '_' + yyyy,
                    extension: '.xlsx',
                    title: 'Case Export ' + dd + '/' + mm + '/' + yyyy,
                    exportOptions: {columns: [0, 1, 2, 3, 4, 5, 6]}
                }]*/
            }];

        makeDatatable(datatables_info);

        var table = $('#activeCasesTable').DataTable();

        //table.columns([6, 7, 8]).visible(false);

        table.on('draw', function() {

            $('.overlay').hide();

        });

        table.on('preDraw', function() {

            $('.overlay').show();

        });

        table.draw();

        $('#activeCasesTable').on('click', 'td', function() {

            var target = $(this).closest('tr');

            var rowData = table.row(this).data();

            highlightRow(target);

        });

    });

</script>