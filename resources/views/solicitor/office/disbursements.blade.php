@extends('layouts.app')
@section('content')
<div class="col-sm-20 window">
    @include('layouts.breadcrumb', [
            'breadcrumbs' =>
                [
                    'solicitors' => 'Solicitors',
                    'office/' . $solicitorOffice->slug => $solicitor->name . ' (' . $solicitorOffice->office_name . ')',
                    'disbursements' => 'Disbursements',
                ]
            ]
        )
    <div id="page-header" class="col-sm-23">
        <h1>{{ $solicitor->name }}</h1>
        <h6>({{ trim($solicitorOffice->office_name) }})</h6>
    </div>
    <div id="content-container" class="hidden">
        <div data-notification="warning" data-hover="Dismiss Notification?" class="col-sm-23 warning-box">
            <p id="currentRowMessage" class="hidden">Price To must be higher than Price From.</p>
            <p id="valueNumberMessage" class="hidden">The value entered must be a number.</p>
            <p id="addRowMessage" class="hidden">You can't add a new row until you have filled in Price To and the Fee</p>
        </div>
    </div>
    <div id="content-area">
        @include('solicitor.office.office-details')
    </div>
    <div class="row col-sm-23">
        <div class="col-sm-21">
            <h1>Disbursements</h1>
        </div>
        <div class="col-sm-3">
            <button class="success-button col-sm-24 ml-4 @if(session()->has('errors')) hidden @endif" id="addNewBtn">Add New</button>
            <button class="success-button col-sm-24 ml-4 @if(!session()->has('errors')) hidden @endif" id="hideNewBtn">Hide New</button>
        </div>
    </div>
    <form method="post">
        {{csrf_field()}}
        <div class="row">
            <div id="added-content-area" class="panel col-sm-23">
                <h4><strong>Government &amp; Third Party</strong></h4>
                <div class="panel-body">
                    <table class="table table-bordered" id="disbursementTable">
                        <thead>
                        <tr>
                            <th id="tick" class="first">
                                <label class="cbContainer">
                                    <input type="checkbox" name="tickall" id="tickall">
                                    <span class="checkmark"></span>
                                </label>
                            </th>
                            <th id="name">Name</th>
                            <th id="transaction">Transaction</th>
                            <th id="type">Type</th>
                            <th id="cost">Cost inc.VAT</th>
                        </tr>
                        </thead>
                    </table>
                </div>
                <button class="success-button col-sm-5 ml-4 mb-3" type="submit" id="save">
                    <a>Save</a>
                </button>
            </div>
        </div>
    </form>
    <form method="post" action="/disbursements">
        {{csrf_field()}}
        <input type="hidden" id="return" name="return" value="{{ $solicitorOffice->slug }}" required />
        <div class="row">
            <div id="content-area" class="add-disbursement-section panel col-sm-23 @if(!session()->has('errors')) hidden @endif">
                <h4><strong>Add Disbursement</strong></h4>
                <div class="panel-body">
                    <div class="row">
                        <div class="form-group col-sm-5 ml-4">
                            <label for="name">Name</label>
                            <input type="text" id="name" name="name" class="form-control" placeholder="Disbursement Name" value="{{ old('name') }}" required />
                        </div>
                        <div class="form-group col-sm-5 ml-4">
                            <label for="cost">Cost</label>
                            <input type="text" id="cost" name="cost" class="form-control" placeholder="£ (inc VAT)" value="{{ old('cost') }}" required />
                        </div>
                        <div class="form-group col-sm-5 ml-4">
                            <label for="transaction">Transaction</label>
                            <select id="transaction" name="transaction" class="form-control" required>
                                <option value="">Please Select</option>
                                <option value="purchase" @if (old('transaction') === 'purchase') selected @endif>Purchase</option>
                                <option value="sale" @if (old('transaction') === 'sale') selected @endif>Sale</option>
                            </select>
                        </div>
                        <div class="form-group col-sm-5 ml-4">
                            <label for="type">Type</label>
                            <select id="type" name="type" class="form-control" required>
                                <option value="">Please Select</option>
                                <option value="case" @if (old('type') === 'case') selected @endif>Case</option>
                                <option value="client" @if (old('type') === 'client') selected @endif>Client(s)</option>
                            </select>
                        </div>
                    </div>
                    <button class="success-button col-sm-5 ml-4 mb-3" type="submit" id="save">
                        <a>Save</a>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
@push('scripts')
<script type="text/javascript">
$(document).ready(function()
{
    $('#addNewBtn').click(() => {
        $('#addNewBtn').hide();
        $('#hideNewBtn').show();
        $('.add-disbursement-section').fadeIn();
    });

    $('#hideNewBtn').click(() => {
        $('#hideNewBtn').hide();
        $('#addNewBtn').show();
        $('.add-disbursement-section').fadeOut();
    });

    var datatables_info =
        [{
            url: '/disbursements/get-disbursements',
            dataTableID: '#disbursementTable',
            ordering: [[1, 'asc']],
            stateSave: true,
            cols :
                [
                    {
                        data: 'id',
                        name: 'id',
                        render: (data, type, full) => `<label class="cbContainer"><input type="checkbox" name="id[]" id="${full.id}" value="${full.id}" ${isChecked(full.id)}><span class="checkmark"></span></label>`, // full.id,
                        orderable: false,
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'transaction',
                        name: 'transaction'
                    },
                    {
                        data: 'type',
                        name: 'type'
                    },
                    {
                        data: 'cost',
                        name: 'cost',
                        render: (data, type, full) => '£' + full.cost,
                    },
                ],
        }];

    window.makeDatatable(datatables_info);

    $('#tickall').click(function () {
        $('input:checkbox').not(this).prop('checked', this.checked);
    });

    var disbursements = {{ json_encode($disbursements) }};

    console.log(disbursements);

    function isChecked(id) {
        for (i = 0; i < disbursements.length; i++) {
            console.log(disbursements[i] + ' ' + id);
            if(Number(disbursements[i]) === Number(id)) return 'checked';
        }

        return null;
    }
});
</script>
@endpush
