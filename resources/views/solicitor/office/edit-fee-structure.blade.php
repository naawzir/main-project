@extends('layouts.app')
@section('content')
    <div class="col-sm-20 window">
        @include('layouts.breadcrumb', [
            'breadcrumbs' =>
                [
                    'solicitors' => 'Solicitors',
                    'edit' => 'Edit',
                    'fee-structures' => 'Fee Structures',
                ]
            ]
        )
        <div id="page-header" class="col-sm-23">
            <h1>Edit Fee Structures: {{ $solicitorOffice->office_name }}</h1>
        </div>
        <div id="content-container" class="hidden">
            <div data-notification="warning" data-hover="Dismiss Notification?" class="col-sm-23 warning-box">
                <p id="currentRowMessage" class="hidden">Price To must be higher than Price From.</p>
                <p id="valueNumberMessage" class="hidden">The value entered must be a number.</p>
                <p id="addRowMessage" class="hidden">You can't add a new row until you have filled in Price To and the Legal Fee</p>
            </div>
        </div>
        <div id="content-area">
            <form id="edit-solicitor-pricing" action="/solicitors/office/{{ $solicitorOffice->slug }}/fee-structures/edit/" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <!--start of Purchase -->
                    <div id="purchase" class="fee-section panel col-sm-23">
                        <div class="panel-body">
                            <div class="full col-sm-23">
                                <div class="row">
                                    <div id="feeScaleHeadingsContainer" class="col-sm-7">
                                        <h6 class="purchase"><strong>Purchase Fee Scale</strong></h6>
                                    </div>
                                    <table class="table nohighlight">
                                        <tbody id="tbodyPurchase">
                                        @php $i = 0 @endphp
                                        @foreach ($feeStrPurchase as $feeStructure)
                                            <tr class="@if ($i == 0) duplicate @endif row" data-id="{{ $feeStructure->slug }}">
                                                <td class="col-sm-1"><input type="hidden" class="pricing_structure_id" value="{{ $feeStructure->id }}" name="id[]" /></td>
                                                <td class="col-sm-5">
                                                    <div class="row">
                                                        <div class="col-sm-8">
                                                            <span><strong>From</strong></span>
                                                            <span class="pound-symbol ">&pound;</span>
                                                        </div>
                                                        <div class="col-sm-16">
                                                            <input readonly class="price_from form-control" type="text" name="price_from[]" value="{{ $feeStructure->price_from }}" placeholder="Enter Value" required>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="col-sm-1"></td>
                                                <td class="col-sm-5 price-to-td">
                                                    <div class="row">
                                                        <div class="col-sm-8">
                                                            <span><strong>To</strong></span>
                                                            <span class="pound-symbol ">&pound;</span>
                                                        </div>
                                                        <div class="col-sm-16">
                                                            <input class="price_to form-control" type="text" name="price_to[]" value="{{ $feeStructure->price_to }}" placeholder="Enter Value" required>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="col-sm-1"></td>
                                                <td class="col-sm-5">
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <span><strong>Legal Fee</strong></span>
                                                            <span class="pound-symbol ">&pound;</span>
                                                        </div>
                                                        <div class="col-sm-18">
                                                            <input class="legal_fee form-control" type="text" name="legal_fee[]" value="{{ $feeStructure->legal_fee }}" placeholder="Enter Value" required>
                                                        </div>
                                                        <input class="case_type" name="case_type[]" type="hidden" value="purchase">
                                                    </div>
                                                </td>
                                                <td class="col-sm-1">
                                                    <p class="text-center pt-2 delete-btn">
                                                        <a href=""><i class="far fa-trash"></i></a>
                                                    </p>
                                                </td>
                                            </tr>
                                            @php $i++ @endphp
                                        @endforeach
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <td colspan="6">
                                                <a href="" class="add-more bold-link">+ Add another fee structure</a>
                                            </td>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end of Purchase -->
                    <!--start of Sale -->
                    <div id="sale" class="fee-section panel col-sm-23">
                        <div class="panel-body">
                            <div class="full col-sm-23">
                                <div class="row">
                                    <div id="feeScaleHeadingsContainer" class="col-sm-7">
                                        <h6 class="sale"><strong>Sale Fee Scale</strong></h6>
                                    </div>
                                    <table class="table nohighlight">
                                        <tbody id="tbodySale">
                                        @php $i = 0 @endphp
                                        @foreach ($feeStrSale as $feeStructure)
                                                <tr class="@if ($i == 0) duplicate @endif row" data-id="{{ $feeStructure->slug }}">
                                                    <td class="col-sm-1"><input type="hidden" class="pricing_structure_id" value="{{ $feeStructure->id }}" name="id[]" /></td>
                                                    <td class="col-sm-5">
                                                        <div class="row">
                                                            <div class="col-sm-8">
                                                                <span><strong>From</strong></span>
                                                                <span class="pound-symbol ">&pound;</span>
                                                            </div>
                                                            <div class="col-sm-16">
                                                                <input readonly class="price_from form-control" type="text" name="price_from[]" value="{{ $feeStructure->price_from }}" placeholder="Enter Value" required>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="col-sm-1"></td>
                                                    <td class="col-sm-5 price-to-td">
                                                        <div class="row">
                                                            <div class="col-sm-8">
                                                                <span><strong>To</strong></span>
                                                                <span class="pound-symbol ">&pound;</span>
                                                            </div>
                                                            <div class="col-sm-16">
                                                                <input class="price_to form-control" type="text" name="price_to[]" value="{{ $feeStructure->price_to }}" placeholder="Enter Value" required>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="col-sm-1"></td>
                                                    <td class="col-sm-5">
                                                        <div class="row">
                                                            <div class="col-sm-6">
                                                                <span><strong>Legal Fee</strong></span>
                                                                <span class="pound-symbol ">&pound;</span>
                                                            </div>
                                                            <div class="col-sm-18">
                                                                <input class="legal_fee form-control" type="text" name="legal_fee[]" value="{{ $feeStructure->legal_fee }}" placeholder="Enter Value" required>
                                                            </div>
                                                            <input class="case_type" name="case_type[]" type="hidden" value="sale">
                                                        </div>
                                                    </td>
                                                    <td class="col-sm-1">
                                                        <p class="text-center pt-2 delete-btn">
                                                            <a href=""><i class="far fa-trash"></i></a>
                                                        </p>
                                                    </td>
                                                </tr>
                                            @php $i++ @endphp
                                        @endforeach
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <td colspan="6">
                                                <a href="" class="add-more bold-link">Add another fee structure</a>
                                            </td>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end of Sale -->
                <div class="clean-panel col-sm-24" style="background:none;border:none;box-shadow:none;">
                    <div class="row">
                        <div class="col-sm-12"></div>
                        <div class="col-sm-6">
                            <a href="/solicitors/office/{{ $solicitorOffice->slug }}">
                                <button class="cancel-button col-sm-22" type="button">Cancel</button>
                            </a>
                        </div>
                        <div class="col-sm-6">
                            <button class="success-button col-sm-20">Save</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@push('scripts')
    <script type="text/javascript" src="/js/solicitor/office/fee-structure.js"></script>
@endpush
@endsection
