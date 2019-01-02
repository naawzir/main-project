@extends('layouts.app')
@section('content')

<div class="col-sm-20 window">
    <div class="col-sm-24 row">
        @include('layouts.breadcrumb', [
            "breadcrumbs" => [
                "disbursements"
            ]
        ])
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

    <div class="row">
        <div id="added-content-area" class="panel col-sm-23">
            <h4><strong>Government &amp; Third Party</strong></h4>
            <div class="panel-body">
                <table class="table table-bordered" id="disbursementTable">
                    <thead>
                    <tr>
                        <th id="name">Name</th>
                        <th id="transaction">Transaction</th>
                        <th id="type">Type</th>
                        <th id="cost">Cost inc.VAT</th>
                        <th id="id"></th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <div class="row">
        <div id="content-area" class="add-disbursement-section panel col-sm-23 @if(!session()->has('errors')) hidden @endif">
            <h4><strong>Add Disbursement</strong></h4>
            <div class="panel-body">

                <form method="post">

                    {{csrf_field()}}

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

                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script type="text/javascript">
    $(document).ready(function()
    {
        $('#addNewBtn').click(function() {
            $('#addNewBtn').hide();
            $('#hideNewBtn').show();
            $('.add-disbursement-section').fadeIn();
        });

        $('#hideNewBtn').click(function() {
            $('#hideNewBtn').hide();
            $('#addNewBtn').show();
            $('.add-disbursement-section').fadeOut();
        });

        var datatables_info =
            [{
                url: '/disbursements/get-disbursements',
                dataTableID: '#disbursementTable',
                ordering: [[0, 'asc']],
                stateSave: true,
                cols :
                    [
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
                            render: (data, type, full) => `£ ${full.cost}`,
                        },
                        {
                            data: 'id',
                            name: 'id',
                            render: (data, type, full) => `<button class="success-button edit col-sm-8" data-id="${full.slug}">Edit</button><p class="col-sm-6 d-inline-block"></p>
                                                            <a class="col-sm-2 d-inline-block delete-button" href="" data-id="${full.slug}"><i class="far fa-trash d-inline-block"></i></a>
                                                            <p class="col-sm-6 d-inline-block"></p>`,
                        },
                    ],
            }];

        window.makeDatatable(datatables_info);

        $('#added-content-area').on('click', '.delete-button', function(e) {
            e.preventDefault();

            let id = $(this).attr("data-id");
            var row = $(this).closest('tr');

            if(typeof id !== 'undefined') {

                $.confirm({
                    title: 'DELETE!',
                    content: 'Are you sure you want to delete this item?',
                    buttons: {
                        confirm: {
                            btnClass: 'btn-success',
                            action: function () {
                                location.href = '/disbursements/' + id + '/destroy/';
                            }
                        },
                        cancel: {
                            btnClass: 'btn-red'
                        }
                    }
                });
            }
        });

        $('#disbursementTable').on('click', '.edit', function () {
            window.location = `/disbursements/${$(this).data('id')}/edit/`;
        });
    });
</script>
@endpush
@endsection
