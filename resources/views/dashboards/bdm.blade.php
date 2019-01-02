@section('tasks')
	<!-- Full width panel -->
	<div class="row">
		<section class="col-sm-1"></section>
		<section id="bdmtasks" class="col-sm-22 panel">
			<h3><strong>Tasks ({{$dashData['bdmTasksCount']}})</strong></h3>
			<div class="panel-body ml-3">
				<table class="table table-bordered" id="bdmTasksTable">
					<thead>
					<tr>
						<th id="due">Due</th>
						<th id="type">Type</th>
						<th id="note">Note</th>
						<th id="view"></th>
					</tr>
					</thead>
				</table>
			</div>
		</section>
	</div>
@endsection
@push('scripts')
	<script type="text/javascript">
        $(document).ready(function()
        {
            var datatables_info =
                [{
                    url: '/tasks/get-tasks-for-user/bdm',
                    dataTableID: '#bdmTasksTable',
                    ordering: [[0, "desc"]],
                    stateSave: true,
                    dom: `tl`,
                    lengthmenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                    displaylength: -1,
                    cols :
                        [{
                            data: 'date_due',
                            name: 'date_due',
                            render: function(data, type, full, meta)
                            {
                                return full.due;
                            }
                        },
						{
							data: 'type',
							name: 'type',
							render: function(data, type, full, meta)
							{
								var dataType = toTitleCase(full.type);
								return '<span class="' + typeArray['Branch'] + '">' + dataType + '</span>';
							}
						},
						{
							data: 'notes',
							name: 'notes',
							render: function(data, type, full, meta)
							{
								return strip(full.notes);
							}
						},
						{
							data: 'id',
							name: 'id',
							render: function(data, type, full, meta)
							{
								return '<a href="/tasks/'+ full.slug +'"><button class="success-button">View</button></a>';
							}
						}]
                }];

            makeDatatable(datatables_info);

            $('.nav-link').click(function(e){
                e.preventDefault();
                $('.nav-link').removeClass('active');
                $(this).addClass('active');
                $('section.clean-panel').not('#' + $(this).attr('data-toggle')).addClass('hidden');
                $('#' + $(this).attr('data-toggle')).removeClass('hidden');
            });
        });
	</script>
@endpush
@include('dashboards.director')
