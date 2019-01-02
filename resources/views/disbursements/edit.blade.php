@extends('layouts.app')
@section('content')
<div class="col-sm-20 window">
    @include('layouts.breadcrumb',
        ['breadcrumbs' =>
            [
                'disbursements' => 'Disbursements',
                'edit' => 'Edit',
                $disbursement->slug => $disbursement->name
            ]
        ]
    )
    <div id="page-header" class="col-sm-24">
        <h1>Disbursements</h1>
    </div>
    <form action="/disbursements/{{ $disbursement->slug }}/edit/" method="post">
        {{csrf_field()}}
        <div id="content-area" class="panel col-sm-23">
            <div class="panel-body">
                <div class="full col-sm-24">
                    <div class="col-sm-12 ml-5">
                        <div class="form-group">
                            <label for="name"><strong>Name</strong></label>
                            <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $disbursement->name) }}" placeholder="Disbursement Name" required />
                        </div>
                        <div class="form-group">
                            <label for="cost"><strong>Cost</strong></label>
                            <input type="text" id="cost" name="cost" class="form-control" value="{{ old('cost', $disbursement->cost )}}" placeholder="£ (inc VAT)" required />
                        </div>
                        <div class="form-group">
                            <label for="transaction"><strong>Transaction</strong></label>
                            <select id="transaction" name="transaction" class="form-control" required>
                                Edit Disbursement
                                <option value="">Please Select</option>
                                <option @if ((old("transaction") === 'purchase')) selected @endif {{$disbursement->transaction === "purchase" ? 'selected' : ''}} value="purchase">Purchase</option>
                                <option @if ((old("transaction") === 'sale')) selected @endif {{$disbursement->transaction === "sale" ? 'selected' : ''}} value="sale">Sale</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="type"><strong>Type</strong></label>
                            <select id="type" name="type" class="form-control" required>
                                <option value="">Please Select</option>
                                <option {{$disbursement->type === "case" ? 'selected' : ''}} value="case">Case</option>
                                <option {{$disbursement->type === "client" ? 'selected' : ''}} value="client">Client(s)</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="clean-panel col-sm-24" style="background:none;border:none;box-shadow:none;">
            <div class="row">
                <div class="col-sm-12"></div>
                <div class="col-sm-6">
                    <a href="/disbursements">
                        <button class="cancel-button col-sm-22" type="button">Cancel</button>
                    </a>
                </div>
                <div class="col-sm-6">
                    <button class="success-button col-sm-22">Save</button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
