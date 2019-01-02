<div class="col-sm-12">
    <label class="listing-label col-sm-9">Date From:</label>
    <input type="text" class="listing-text datepicker branch-performance-filter col-sm-13" id="dateFromFilter" placeholder="Please select" value="{{ date('d/m/Y', strtotime('midnight first day of this month')) }}">
</div>

<div class="col-sm-12">
    <label class="listing-label col-sm-9">Date To:</label>
    <input type="text" class="listing-text datepicker branch-performance-filter col-sm-13" id="dateToFilter" placeholder="Please select" value="{{ date('d/m/Y', time()) }}">
</div>

<p style="border:1px solid blue;" id="dateFrom" class="hidden">{{ strtotime('midnight first day of this month') }}</p>
<p style="border:1px solid red;" id="dateTo" class="hidden">{{ time() }}</p>