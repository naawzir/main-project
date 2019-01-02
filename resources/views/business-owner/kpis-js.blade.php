<script>

    $(document).ready(function() {

        $("#branchesFilter").val('');

        $("#dateFilter").val('');

        sessionStorage.clear();

        $("#dateFilter").change(function() {

            var date = $(this).val();

            var date2 = date.substr(3, 2) + "/" + date.substr(0, 2) + "/" + date.substr(6, 4);

            var dateUnix = Math.round((new Date(date2)).getTime() / 1000);

            if(date != '') {

                $("#showingFrom").text('Showing from ' + date);

                $("#date").text(dateUnix);

            } else {

                $("#showingFrom").text('Showing MTD (default)');

                $("#date").text({{ strtotime('midnight first day of this month') }});

            }

        });

        $("#branchesFilter").change(function() {

            var branchName = $("#branchesFilter option:selected").text();

            if(branchName) {

                $("#branchName").text(branchName);

            } else {

                $("#branchName").text('All branches');

            }

        });

        $(".kpi").click(function() {

            var kpi = $(this).attr("id");

            var url = "/cases?status=" + kpi + '&agent-id=';

            var date = $("#date").text();

            url += '&date=' + date;

            url += '&branch-performance=' + true;

            var branchId = $("#branchesFilter").val();

            if(branchId !='') {

                url += '&branch-id=' + branchId;

            } else {

                url += '&my-branches=true';
            }

            location.href = url;

        });

        var d = new Date();
        var currMonth = d.getMonth();
        var currYear = d.getFullYear();
        var startDate = new Date(currYear, currMonth, 1);

        $( ".datepicker" ).datepicker({
            changeMonth: true,
            changeYear: true,
            firstDay: 1,
            dateFormat: 'dd/mm/yy',
            maxDate: new Date,
            setDate : startDate,
        });

        $("#resetFiltersBtn").click(function(ev) {

            ev.preventDefault();

            $("#branchesFilter").val('');
            $("#dateFilter").val('');
            $("#date").text({{ strtotime('midnight first day of this month') }});

            var branchId = $("#branchesFilter").val();
            var date = $("#dateFilter").val();

            $("#showingFrom").text('Showing MTD (default)');
            $("#branchName").text('All branches');

            const data = {

                branchId : branchId,
                date : date

            };

            const ajaxRequest = tcp.xhr.get('/branch/kpis-ajax', data);

            ajaxRequest.done(function(data) {

                $("#prospectsCount").text(data.branchPerformanceKpi.prospect);
                $("#instructionsCount").text(data.branchPerformanceKpi.instructed);
                $("#unpanelledInstructionsCount").text(data.branchPerformanceKpi.instructed_unpanelled);
                $("#completionsCount").text(data.branchPerformanceKpi.completed);
                $("#abortedCount").text(data.branchPerformanceKpi.aborted);

            });

            ajaxRequest.fail(function(data) {

                alert("failed");

            });

            var table = $('#branchesTable').DataTable();

            table
                .column(9)
                .search('');

            table.draw();

        });

        $(".branch-performance-filter").change(function() {

            var branchId = $("#branchesFilter").val();

            var date = $("#date").text();

            const data = {

                branchId : branchId,
                date : date

            };

            const ajaxRequest = tcp.xhr.get('/businessowner/kpis-ajax', data);

            ajaxRequest.done(function(data) {

                $("#prospectsCount").text(data.branchPerformanceKpi.prospect);
                $("#instructionsCount").text(data.branchPerformanceKpi.instructed);
                $("#unpanelledInstructionsCount").text(data.branchPerformanceKpi.instructed_unpanelled);
                $("#completionsCount").text(data.branchPerformanceKpi.completed);
                $("#abortedCount").text(data.branchPerformanceKpi.aborted);

            });

            ajaxRequest.fail(function(data) {

                alert("failed");

            });

        });

    });

</script>
