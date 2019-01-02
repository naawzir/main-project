@extends('layouts.app')
@section('content')
    <div class="col-sm-20 window">
	<div class="row">
		<div class="col-sm-24">
			@include('layouts.breadcrumb',
                ['breadcrumbs' =>
                    [
                      'solicitors/office/' . $solicitorOffice->slug =>
					  $solicitorOffice->solicitor->name . ' (' . $solicitorOffice->office_name . ')'
					]
                ]
             )
			<div class="row" id="Title">
				<div class="col-sm-17">
					<h1><strong>{{$solicitorOffice->solicitor->name}} , {{$solicitorOffice->office_name}}</strong></h1>
				</div>
			</div>
            <div class="row" id="AddressRating">
				<div class="col-sm-1"></div>
				<div class="panel col-sm-22">
					<div class="row">
                        <div class="col-sm-6">
					        <p style="margin-left:10px;"><strong>{{$solicitorOffice->address->getAddress()}}</strong></p>
                        </div>
                    <div class="col-sm-10"></div>
                    <div class="row">
                    <table class="table table-bordered" id="">
                        <thead>
                            <tr>
                            <th>Agent Rating</th>
                            </tr>
                        </thead>
                        <tr>
                            <td>
                                <p>{{$solicitorOffice->getAgentRating() / 2}}</p>
                            </td>
                        </tr>
                    </table>
                    </div>
                    <div class="row">
                    <div class="col-sm-1"></div>
                    <table class="table table-bordered" id="">
                        <thead>
                            <tr>
                            <th>Customer Rating</th>
                            </tr>
                        </thead>
                        <tr>
                            <td>
                                <p>{{$solicitorOffice->getCustomerRating() / 2}}</p>
                            </td>
                        </tr>
                    </table>
                    </div>
					</div>
					<a href="/{{$solicitorOffice->solicitor->url}}" target="_blank">{{$solicitorOffice->solicitor->url}}</a>
                </div>
			</div>

            <div class="col-sm-10"></div>

            <div class="row mb-3">
                <div class="col-sm-1"></div>
                @if($partnership === 'remove')
                    <button class="cancel-button" id="removeFromPanelBtn">Remove from panel</button>
                @else
                    <button class="success-button" id="addToPanelBtn">Add to panel</button>
                @endif
            </div>

            <div class="row" id="FeeScales">
                <div class="col-sm-1"></div>
				<div class="panel col-sm-7">
					<div class="row">
                    <section><h4><strong>Purchase Fee Scale</strong></h4></section>
                        <table class="table table-bordered" id="">
                            <thead>
                                <tr>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>Legal Fee</th>
                                </tr>
                            </thead>
                            @foreach($purchaseFees as $fee)
                                <tr>
                                    <td>£{{ isset($fee->price_from) ? $fee->price_from : '' }}</td>
                                    <td>£{{ isset($fee->price_to) ? $fee->price_to : '' }}</td>
                                    <td class="primary-dark-font"><strong>£{{ isset($fee->legal_fee) ? $fee->legal_fee : '' }}</strong></td>
                                </tr>
                            @endforeach
                        </table>
					</div>
				</div>
                <div class="col-sm-1"></div>
                <div class="panel col-sm-7">
					<div class="row">
                    <section><h4><strong>Sale Fee Scale</strong></h4></section>
                        <table class="table table-bordered" id="">
                            <thead>
                                <tr>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>Legal Fee</th>
                                </tr>
                            </thead>
                            @foreach($saleFees as $fee)
                                <tr>
                                    <td>£{{ isset($fee->price_from) ? $fee->price_from : '' }}</td>
                                    <td>£{{ isset($fee->price_to) ? $fee->price_to : '' }}</td>
                                    <td class="primary-dark-font"><strong>£{{ isset($fee->legal_fee) ? $fee->legal_fee : '' }}</strong></td>
                                </tr>
                            @endforeach
                        </table>
					</div>
                    </div>
                    <div class="col-sm-1"></div>
                    @if (!empty($additionalFees))
                    <div class="panel col-sm-6">
                        <div class="row">
                        <section><h4><strong>Additonal Fees</strong></h4></section>
                            <table class="table table-bordered" id="">
                                <thead>
                                    <tr>
                                        <td>New Mortage Fee</td>
                                        <td>{{$additionalFees->mortgage}}</td>
                                    </tr>
                                    <tr>
                                        <td>Mortage Red.Fee</td>
                                        <td>{{$additionalFees->mortgage_redemption}}</td>
                                    </tr>
                                    <tr>
                                        <td>Leasehold</td>
                                        <td>{{$additionalFees->leasehold}}</td>
                                    </tr>
                                    <tr>
                                        <td>Archive Fee</td>
                                        <td>{{$additionalFees->archive}}</td>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                        @endif
				</div>
			</div>
        </div>
	</div>
</div>

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {

            $('#removeFromPanelBtn').on('click', function() {
                $.confirm({
                    title: 'DELETE!',
                    content: 'Are you sure you want to remove this Solicitor Office from your panel?',
                    buttons: {
                        confirm: {
                            btnClass: 'btn-success',
                            action: function () {
                                window.location.href = '/solicitors/office/{{$solicitorOffice->slug}}/remove-from-panel/'
                            }
                        },
                        cancel: {
                            btnClass: 'btn-red'
                        }
                    }
                });
            });

            $('#addToPanelBtn').on('click', function() {
                $.confirm({
                    title: 'ADD TO PANEL!',
                    content: 'Are you sure you want to add this Solicitor Office to your panel?',
                    buttons: {
                        confirm: {
                            btnClass: 'btn-success',
                            action: function () {
                                window.location.href='/solicitors/office/{{$solicitorOffice->slug}}/add-to-panel/'
                            }
                        },
                        cancel: {
                            btnClass: 'btn-red'
                        }
                    }
                });
            });

        });
    </script>
@endpush
@endsection
