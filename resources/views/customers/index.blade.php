@extends('layouts.app')
@section('content')

	<div class="col-sm-20 window">

    	 @include('layouts.breadcrumb', array("breadcrumbs" => ["customers"]))

    	<h1>Customers</h1>

   		<div id="content-area" class="panel col-sm-23">
        
        <h4><strong>Customers List</strong></h4>

          <div class="panel-body">
              
            <table class="table table-bordered" id="customerTable">
              
              <thead>
                <tr>
                  <th id="name">Name</th>
                  <th id="status">Status</th>
                  <th id="aml-searches">AML Searches Completed</th>
                  <th id="last-contacted">Last Contacted</th>
                </tr>
              </thead>
              
            </table>
          
          </div>
        
        </div>

   		</div>

   	</div>
@push('scripts')
  <script type="text/javascript">
    $(document).ready(function()
    {
	    var datatables_info =
		    [{
			    url: '/customer/show',
			    dataTableID: '#customerTable',
			    ordering: [[0, "asc"]],
			    stateSave: true,
			    cols :
			    [
				    {
					    data: 'Name',
					    name: 'Name',
					    render: function(data, type, full, meta)
					    {
						    return full.title + ' ' + full.forename + ' ' + full.surname;
					    }
				    },
				    {
					    data: 'Status',
					    name: 'Status'
				    },
				    {
					    data: 'AMl Seaches Completed',
					    name: 'AMl Seaches Completed'
				    },
				    {
					    data: 'Last Contacted',
					    name: 'Last Contacted'
				    }
			    ]
		    }];

      // makeDatatable(datatables_info);

    });
  </script>
@endpush
@endsection
