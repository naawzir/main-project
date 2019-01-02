<script>

    $(document).ready(function() {
        $("#dateFilter").val('');

        var branchId = $("#branchId").text();

        sessionStorage.clear();

        $(".branch-performance-filter").change(function() {

            let dateFrom = $('#dateFromFilter').val();
            dateFrom = dateFrom.substr(3, 2) + "/" + dateFrom.substr(0, 2) + "/" + dateFrom.substr(6, 4);
            const dateFromUnix = Math.round((new Date(dateFrom)).getTime() / 1000);

            let dateTo = $('#dateToFilter').val();
            dateTo = dateTo.substr(3, 2) + "/" + dateTo.substr(0, 2) + "/" + dateTo.substr(6, 4);
            const dateToUnix = Math.round((new Date(dateTo)).getTime() / 1000);

            if(dateFromUnix > dateToUnix) {
                dateFrom = $('#dateToFilter').val();
                dateTo = $('#dateFromFilter').val();

                $('#dateFromFilter').val(dateFrom);
                $('#dateToFilter').val(dateTo);
            }

            dateFrom = $('#dateFromFilter').val();

            if(dateFrom != '') {
                $("#showingFrom").text('Showing from ' + dateFrom);
                $("#dateFrom").text(dateFromUnix);
                $("#dateTo").text(dateToUnix);
            } else {
                $("#showingFrom").text('Showing MTD (default)');
                $("#dateFrom").text({{ strtotime('midnight first day of this month') }});
                $("#dateTo").text('');
            }

            const data = {
                branchId : branchId,
                dateFrom : dateFromUnix,
                dateTo : dateToUnix
            };

            const ajaxRequest = tcp.xhr.get('/branch/kpis-ajax', data);

            ajaxRequest.done(function(data) {
                $("#prospectsCount").text(data.branchPerformanceKpi.prospect);
                $("#instructionsCount").text(data.branchPerformanceKpi.instructed);
                var conversion = data.conversions.prospectToInstructed;
                var percentSymbol = conversion[0]['percent'];

                fillDonut('#conversion', conversion);

                if(percentSymbol) {
                    $("#conversion .chart-number").append('%');
                }

                $("#unpanelledInstructionsCount").text(data.branchPerformanceKpi.instructed_unpanelled);
                $("#completionsCount").text(data.branchPerformanceKpi.completed);
                $("#abortedCount").text(data.branchPerformanceKpi.aborted);
            });

        });

        $(".kpi").click(function() {
            var kpi = $(this).attr("id");
            var agentId = $("#agencyId").text();
            var url = "/cases/?clear=true&status=" + kpi + '&agent-id=' + agentId;
            var date = $("#date").text();
            url += '&date=' + date;
            url += '&branch-id=' + branchId;
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
            setDate : startDate
        });

        $("#resetFiltersBtn").click(function(ev) {
            ev.preventDefault();

            $('#dateFromFilter').val('{{ date('d/m/Y', strtotime('midnight first day of this month')) }}');
            $('#dateToFilter').val('{{ date('d/m/Y', time()) }}');
            $('#dateFrom').text({{ strtotime('midnight first day of this month') }});
            $('#dateTo').text({{ time() }});

            const dateFromUnix = $('#dateFrom').text();
            const dateToUnix = $('#dateTo').text();

            $("#showingFrom").text('Showing MTD (default)');

            const data = {
                branchId : branchId,
                dateFrom : dateFromUnix,
                dateTo : dateToUnix
            };

            const ajaxRequest = tcp.xhr.get('/branch/kpis-ajax', data);

            ajaxRequest.done(function(data) {
                $("#prospectsCount").text(data.branchPerformanceKpi.prospect);
                $("#instructionsCount").text(data.branchPerformanceKpi.instructed);
                var conversion = data.conversions.prospectToInstructed;
                var percentSymbol = conversion[0]['percent'];

                fillDonut('#conversion', conversion);

                if(percentSymbol) {
                    $("#conversion .chart-number").append('%');
                }

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
