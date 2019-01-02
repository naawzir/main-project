<script>

    $(document).ready(function() {
        $("#branchesFilter").val('');
        $("#dateFilter").val('');

        sessionStorage.clear();

        $("#branchesFilter").change(function() {
            var branchName = $("#branchesFilter option:selected").text();
            if(branchName) {
                $("#branchName").text(branchName);
            } else {
                $("#branchName").text('All branches');
            }
        });

        $(".kpi").click(function() {
            const count = $(this).find("p span").text();
            let url = '';
            if (count > 0) {
                var kpi = $(this).attr("id");
                if ($(this).hasClass('agent-dashboard-kpi')) {
                    url = '/cases/?clear=true&status=' + kpi + '&transaction=&account-manager=&agent-id=&date={{ strtotime('midnight first day of this month') }}&user-id-agent={{ Auth::user()->id }}';
                    location.href = url;
                } else {
                    url = "/cases/?clear=true&status=" + kpi + '&agent-id=';
                    const date = $("#date").text();
                    url += '&date=' + date;
                    url += '&branch-performance=' + true;
                    const branchId = $("#branchesFilter").val();
                    if(branchId !='') {
                        url += '&branch-id=' + branchId;
                    } else {
                        url += '&my-branches=true';
                    }
                    location.href = url;
                }
            } else {
                $(this).css('cursor', 'default');
            }
        });

        const d = new Date();
        // First Date Of the month
        const startDateFrom = new Date(d.getFullYear(), d.getMonth(), 1);
        const currMonth = d.getMonth();
        const currYear = d.getFullYear();
        const startDate = new Date(currYear, currMonth, 1);

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
            $('#dateFromFilter').val('{{ date('d/m/Y', strtotime('midnight first day of this month')) }}');
            $('#dateToFilter').val('{{ date('d/m/Y', time()) }}');
            $('#dateFrom').text({{ strtotime('midnight first day of this month') }});
            $('#dateTo').text({{ time() }});
            var branchId = $("#branchesFilter").val();

            const dateFromUnix = $('#dateFrom').text();
            const dateToUnix = $('#dateTo').text();

            $("#showingFrom").text('Showing MTD (default)');
            $("#branchName").text('All branches');

            const data = {
                branchId : branchId,
                dateFrom : dateFromUnix,
                dateTo : dateToUnix
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
    });

</script>
