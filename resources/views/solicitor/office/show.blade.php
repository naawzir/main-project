@extends('layouts.app')
@section('content')
<div class="col-sm-20 window">
    @include('layouts.breadcrumb',
        ['breadcrumbs' =>
            [
                'solicitors' => 'Solicitor Market Place',
                'office/' . $solicitorOffice->slug => $solicitor->name . ' (' . trim($solicitorOffice->office_name) . ')'
            ]
        ]
    )
    <div id="added-content-area">
        <div id="page-header" class="col-sm-23">
            <div class="row">
                <div class="col-sm-20">
                    <h1><strong>{{ $solicitor->name }}</strong></h1>
                    <h6><strong>({{ trim($solicitorOffice->office_name) }})</strong></h6>
                </div>
                <div class="col-sm-3">
                    <a href="/solicitors/{{ $solicitor->slug }}/office/create">
                        <button class="success-button col-sm-24 p-2" id="addoffice">Add Another Office</button>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div id="content-area">
        <div class="panel-body mb-2">
            <div class="row">
                @section('editarea')
                    <button class="success-button col-sm-24 p-2" type="button" id="editoffice">Edit</button>
                @endsection
                @include('solicitor.office.office-details')
                <div class="clean-panel col-sm-23 pb-3">
                    <div class="row">
                        @php $userRole = $user->userRole()->first(); @endphp
                        <div class="col-sm-1"></div>
                        @if($userRole->dashboard_title === 'bdm')
                            <div class="col-sm-3">
                                <button class="success-button col-sm-24 p-2" type="button" id="saveexit">Save & Exit</button>
                            </div>
                            <div class="col-sm-1"></div>
                        @else
                            <div class="col-sm-3">
                                <button class="success-button col-sm-24 p-2" type="button" id="exit">Save & Exit</button>
                            </div>
                            <div class="col-sm-1"></div>
                        @endif
                    <!-- Only the BDM can view this and only if the Solicitor Office is "ready" to go to the next stage -->
                        @if($userRole->dashboard_title === 'bdm' && $solicitorOffice->canBeSubmitted('Pending'))
                            <div class="col-sm-5" id="panelManagerDiv">
                                <button class="cancel-button col-sm-23 p-2" type="button" id="panelManagerSubmissionBtn">Submit To Panel Manager</button>
                            </div>
                        @endif
                        @if($userRole->dashboard_title === 'panel-manager')
                            <div class="col-sm-3 hidden" id="tmDiv">
                                <button class="cancel-button col-sm-23 p-2" type="button" id="TMSubmissionBtn">Submit To TM</button>
                            </div>
                            <div class="col-sm-5 hidden" id="marketDiv">
                                <button class="cancel-button col-sm-23 p-2" type="button" id="addToMarketSubmissionBtn">Add To Marketplace</button>
                            </div>
                        @endif
                        <div class="col-sm-2"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="clean-panel col-sm-23">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a data-toggle="pricing" class="nav-link active" href="#">Fee Structures</a>
                </li>
                <li class="nav-item">
                    <a data-toggle="additional" class="nav-link" href="#">Additional Fees</a>
                </li>
            </ul>
            <!-- Full width panel -->
            <section id="pricing" class="panel-body">
                <div class="row mb-2 mr-2">
                    <div class="col-sm-20 float-left m-2">
                        <h3><strong>Fee Structures</strong></h3>
                    </div>
                    <div class="col-sm-3 float-right m-2">
                        @if(count($solicitorOffice->feeStructures) > 0)
                            <a href="/solicitors/office/{{ $solicitorOffice->slug }}/fee-structures/edit">
                                <button class="success-button col-sm-24 p-2" type="button">Edit</button>
                            </a>
                        @elseif (count($solicitorOffice->feeStructures) === 0)
                            <a href="/solicitors/office/{{ $solicitorOffice->slug }}/fee-structures/create">
                                <button class="success-button col-sm-24 p-2" type="button">Create</button>
                            </a>
                        @endif
                    </div>
                </div>
                <div class="panel-body">
                    <table class="table table-bordered" id="pricingStructureTable">
                        <thead>
                        <tr>
                            <th>From</th>
                            <th>To</th>
                            <th>Legal Fee</th>
                            <th>Case Type</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </section>
            <section id="additional" class="panel-body hidden">
                <div class="row mb-2 mr-2">
                    <div class="col-sm-20 float-left m-2">
                        <h3><strong>Additional Fees</strong></h3>
                    </div>
                    <div class="col-sm-3 float-right m-2">
                        @if(!empty($additionalFee))
                            <a href="/solicitors/office/{{ $solicitorOffice->slug }}/additional-fees/edit">
                                <button class="success-button col-sm-24 p-2" type="button">Edit</button>
                            </a>
                        @else
                            <a href="/solicitors/office/{{ $solicitorOffice->slug }}/additional-fees/create">
                                <button class="success-button col-sm-24 p-2" type="button">Create</button>
                            </a>
                        @endif
                    </div>
                    @if(!empty($additionalFee))
                        <div class="ml-5">
                            <p>New Mortgage Fee: <span class="pound-symbol">&pound;</span>{{ $additionalFee->mortgage }}</p>
                            <p>Mortgage Red. Fee: <span class="pound-symbol">&pound;</span>{{ $additionalFee->mortgage_redemption }}</p>
                            <p>Leasehold: <span class="pound-symbol">&pound;</span>{{ $additionalFee->leasehold }}</p>
                            <p>Archive Fee: <span class="pound-symbol">&pound;</span>{{ $additionalFee->archive }}</p>
                        </div>
                    @endif
            </section>
        </div>
        <div class="panel col-sm-23">
            <div class="row mb-2 mr-2">
                <div class="col-sm-19">
                    <h3><strong>Staff</strong></h3>
                </div>
                <div class="col-sm-1 float-right m-2">
                </div>
                <div class="col-sm-3 float-right m-2">
                    <a href="/solicitors/office/{{ $solicitorOffice->slug }}/user/create">
                        <button class="success-button col-sm-24 p-2" id="addstaff">Add Staff</button>
                    </a>
                </div>
            </div>
            <div class="panel-body">
                <table class="table table-bordered" id="staffTable">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Telephone</th>
                        <th>Email</th>
                        <th></th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
        <div class="panel col-sm-23">
            <div class="row mb-2 mr-2">
                <div class="col-sm-24">
                    <h3><strong>Disbursements: Government &amp; Third Party</strong></h3>
                    <div class="col-sm-3 float-right m-2">
                        @if(!empty($disbursements))
                            <a href="/solicitors/office/{{ $solicitorOffice->slug }}/disbursements">
                                <button class="success-button col-sm-24 p-2" type="button">Edit</button>
                            </a>
                        @else
                            <a href="/solicitors/office/{{ $solicitorOffice->slug }}/disbursements">
                                <button class="success-button col-sm-24 p-2" type="button">Create</button>
                            </a>
                        @endif
                    </div>
                    <div class="panel-body">
                        <table class="table table-bordered nohighlight" id="disbursementsTable">
                            <thead>
                            <tr>
                                <th id="name">Name</th>
                                <th id="transaction">Transaction</th>
                                <th id="type">Type</th>
                                <th id="cost">Cost inc.VAT</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($disbursements as $disbursement)
                                <tr>
                                    <td>{{$disbursement->name}}</td>
                                    <td>{{$disbursement->transaction}}</td>
                                    <td>{{$disbursement->type}}</td>
                                    <td>{{$disbursement->cost}}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">
                                        <p class="text-center">You don't have any disbursements.</p>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
    <script type="text/javascript">
        window.OfficeId = {};
        OfficeId.value = '{{ $solicitorOffice->slug }}';

        $(document).ready(() => {
            $('#saveexit')
                .click(() => {
                    const dataf = {
                        slug: '{{ Uuid::generate(4) }}',
                        title: 'Complete Onboarding',
                        target_id: '{{ $solicitorOffice->id }}',
                        target_type: 'solicitor-office',
                        type: 'complete-onboarding',
                        notes: 'Please complete the onboarding process for <strong>{{ $solicitor->name }} {{ $solicitorOffice->office_name }}</strong>',
                        date_due: '{{ time() }}',
                        assigned_to: '{{ $user->id }}',
                    };

                    const ajaxRequest = window.tcp.xhr.post('/tasks/create', dataf);

                    ajaxRequest.done(() => {
                        window.location = '/solicitors/';
                    });

                    ajaxRequest.fail((error) => {
                        window.getAlertResponse('warning-box error', error);
                    });
                });

            $('#editoffice')
                .click(() => {
                    window.location = '/solicitors/office/{{ $solicitorOffice->slug }}/edit';
                });
        });
        @if ($solicitorOffice->canBeSubmitted('Onboarding'))
        $("#tmDiv")
            .removeClass('hidden');
        @elseif ($solicitorOffice->status == 'SentToTM')
        $("#marketDiv")
            .removeClass('hidden');
        @endif
    </script>
    <script type="text/javascript" src="/js/solicitor/office/datatables.js"></script>
@endpush

