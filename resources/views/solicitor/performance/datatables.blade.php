$(document).ready(function()
{
var datatables_info =
[{
url: '/solicitors/getrecords',
dataTableID: '#solicitorTable',
ordering: [[0, "asc"]],
stateSave: true,
cols :
[
{
data: 'Solicitor',
name: 'Solicitor',
render: function(data, type, full, meta)
{
return full.Solicitor + '<br />' + full.Location;
}
},
{
data: 'AverageCompletion',
name: 'AverageCompletion'
},
{
data: 'AgentRating',
name: 'AgentRating'
},
{
data: 'OfficeId',
name: 'OfficeId',
render: function(data, type, full, meta)
{
var url = '/solicitors/office/' + full.OfficeId;
return '<a href="' + url + '"><button class="col-sm-24 success-button">View More</button></a>' ;
}
},
{
data: 'Postcode',
name: 'Postcode'
}
]
}];

$dataTables_filter =
[{
input_id: '#filterAverage',
col_ref: 1,
type: 'numeric',
max: '66',
dataTableID: '#solicitorTable',
stateSave: true
},
{
input_id: '#filterPostcode',
col_ref: 4,
type: 'text',
dataTableID: '#solicitorTable',
stateSave: true,
},
{//temp use of this to use as alpha filter until we get code
input_id: '#filterRadius',
col_ref: 0,
type: 'alpha',
make_options: true,
dataTableID: '#solicitorTable',
stateSave: true,
search_first: true
}];

makeDatatable(datatables_info);

setTimeout(function()
{
initalizeSelectBoxItems($dataTables_filter);
}, 300);

var table = $('#solicitorTable').DataTable();

table.columns(4).visible(false);

});
