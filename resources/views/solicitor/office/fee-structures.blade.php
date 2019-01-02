@extends('layouts.app')
@section('content')
    <div class="col-sm-20 window">
        @include('layouts.breadcrumb', [
            'breadcrumbs' =>
                [
                    'solicitors' => 'Solicitors',
                    'create' => 'Create',
                    'fees' => 'Fees',
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
            <div class="clean-panel col-sm-23 pb-2">
                <div class="row">
                    <div class="col-sm-6">
                        <h5>Set new Fee Scale for</h5>
                    </div>
                    <div class="col-sm-8">
                        <select id="feeScaleType">
                            <option value="purchase">Purchase</option>
                            <option value="sale">Sale</option>
                            <option value="salePurchase">Sale &amp; Purchase</option>
                        </select>
                    </div>
                    <div class="col-sm-6"></div>
                    <div class="col-sm-4">
                        <h6 class="float-right pr-2">
                            <a class="bold-link" href="/solicitors/office/{{ $solicitorOffice->slug }}">Skip this</a>
                        </h6>
                    </div>
                </div>
            </div>
            <form id="add-pricing-band" action="/solicitors/office/{{$solicitorOffice->slug}}/fee-structures/create/" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <!--start of Purchase -->
                <div id="purchase" class="fee-section panel col-sm-23">
                    <div class="panel-body">
                        <div class="full col-sm-23">
                            <div class="row">
                                <div class="col-sm-7">
                                    <h6 class="purchase"><strong>Purchase Fee Scale</strong></h6>
                                </div>
                                <table class="table nohighlight">
                                    <tbody id="tbodyPurchase">
                                    @if (empty($defaultOffice) || count($feeStrPurchase) === 0)
                                        <tr class="duplicate row">
                                            <td class="col-sm-1"></td>
                                            <td class="col-sm-5">
                                                <div class="row">
                                                    <div class="col-sm-8">
                                                        <span><strong>From</strong></span>
                                                        <span class="pound-symbol ">&pound;</span>
                                                    </div>
                                                    <div class="col-sm-16">
                                                        <input readonly class="price_from form-control" type="text" name="price_from[]" placeholder="Enter Value" value="0.00">
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
                                                        <input class="price_to form-control text-input" type="text" name="price_to[]" placeholder="Enter Value" >
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="col-sm-1"></td>
                                            <td class="col-sm-4">
                                                <p class="pt-2"><input class="unlimited-checkbox unlimited-checkbox-purchase" type="checkbox"/>Unlimited</p>
                                            </td>
                                            <td class="col-sm-1"></td>
                                            <td class="col-sm-5">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <span><strong>Fee</strong></span>
                                                        <span class="pound-symbol ">&pound;</span>
                                                    </div>
                                                    <div class="col-sm-18">
                                                        <input class="legal_fee form-control text-input" type="text" name="legal_fee[]" placeholder="Enter Value" >
                                                    </div>
                                                    <input name="case_type[]" type="hidden" value="purchase">
                                                </div>
                                            </td>
                                            <td class="col-sm-1">
                                                <p class="text-center pt-2 delete-btn delete-btn-purchase">
                                                    <a href=""><i class="far fa-trash"></i></a>
                                                </p>
                                            </td>
                                        </tr>
                                    @else
                                        @php $i = 0 @endphp
                                        @foreach ($feeStrPurchase as $feeStructure)
                                            <tr class="@if ($i == 0) duplicate @endif row">
                                                <td class="col-sm-1"></td>
                                                <td class="col-sm-5">
                                                    <div class="row">
                                                        <div class="col-sm-8">
                                                            <span><strong>From</strong></span>
                                                            <span class="pound-symbol">&pound;</span>
                                                        </div>
                                                        <div class="col-sm-16">
                                                            <input readonly class="price_from form-control" type="text" name="price_from[]" placeholder="Enter Value" value="{{ $feeStructure->price_from }}">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="col-sm-1"></td>
                                                <td class="col-sm-5 price-to-td">
                                                    <div class="row">
                                                        <div class="col-sm-8">
                                                            <span><strong>To</strong></span>
                                                            <span class="pound-symbol">&pound;</span>
                                                        </div>
                                                        <div class="col-sm-16">
                                                            <input class="price_to form-control text-input" type="text" name="price_to[]" value="{{ $feeStructure->price_to }}" placeholder="Enter Value" >
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="col-sm-1"></td>
                                                <td class="col-sm-4">
                                                    <p class="pt-2"><input class="unlimited-checkbox unlimited-checkbox-purchase" type="checkbox"/>Unlimited</p>
                                                </td>
                                                <td class="col-sm-1"></td>
                                                <td class="col-sm-5">
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <span><strong>Fee</strong></span>
                                                            <span class="pound-symbol ">&pound;</span>
                                                        </div>
                                                        <div class="col-sm-18">
                                                            <input class="legal_fee form-control text-input" type="text" name="legal_fee[]" value="{{ $feeStructure->legal_fee }}" placeholder="Enter Value" >
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
                                    @endif
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
                <div id="sale" class="fee-section panel col-sm-23 hidden">
                    <div class="panel-body">
                        <div class="full col-sm-23">
                            <div class="row">
                                <div class="col-sm-7">
                                    <h6 class="sale"><strong>Sale Fee Scale</strong></h6>
                                </div>
                                <table class="table nohighlight">
                                    <tbody id="tbodySale">
                                    @if (empty($defaultOffice) || count($feeStrSale) === 0)
                                        <tr class="duplicate row">
                                            <td class="col-sm-1"></td>
                                            <td class="col-sm-5">
                                                <div class="row">
                                                    <div class="col-sm-8">
                                                        <span><strong>From</strong></span>
                                                        <span class="pound-symbol ">&pound;</span>
                                                    </div>
                                                    <div class="col-sm-16">
                                                        <input readonly class="price_from form-control" type="text" name="price_from[]" placeholder="Enter Value" value="0.00">
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
                                                        <input class="price_to form-control" type="text" name="price_to[]" placeholder="Enter Value" >
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="col-sm-1"></td>
                                            <td class="col-sm-4">
                                                <p class="pt-2"><input class="unlimited-checkbox unlimited-checkbox-sale" type="checkbox"/>Unlimited</p>
                                            </td>
                                            <td class="col-sm-1"></td>
                                            <td class="col-sm-5">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <span><strong>Fee</strong></span>
                                                        <span class="pound-symbol ">&pound;</span>
                                                    </div>
                                                    <div class="col-sm-18">
                                                        <input class="legal_fee form-control" type="text" name="legal_fee[]" placeholder="Enter Value" >
                                                    </div>
                                                    <input name="case_type[]" type="hidden" value="sale">
                                                </div>
                                            </td>
                                            <td class="col-sm-1">
                                                <p class="text-center pt-2 delete-btn">
                                                    <a href=""><i class="far fa-trash"></i></a>
                                                </p>
                                            </td>
                                        </tr>
                                    @else
                                        @php $i = 0 @endphp
                                        @foreach ($feeStrSale as $feeStructure)
                                            <tr class="@if ($i == 0) duplicate @endif row">
                                                <td class="col-sm-1"></td>
                                                <td class="col-sm-5">
                                                    <div class="row">
                                                        <div class="col-sm-8">
                                                            <span><strong>From</strong></span>
                                                            <span class="pound-symbol ">&pound;</span>
                                                        </div>
                                                        <div class="col-sm-16">
                                                            <input readonly class="price_from form-control" type="text" name="price_from[]" placeholder="Enter Value" value="{{ $feeStructure->price_from }}">
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
                                                            <input class="price_to form-control" type="text" name="price_to[]" value="{{ $feeStructure->price_to }}" placeholder="Enter Value" >
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="col-sm-1"></td>
                                                <td class="col-sm-4">
                                                    <p class="pt-2"><input class="unlimited-checkbox unlimited-checkbox-sale" type="checkbox"/>Unlimited</p>
                                                </td>
                                                <td class="col-sm-1"></td>
                                                <td class="col-sm-5">
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <span><strong>Fee</strong></span>
                                                            <span class="pound-symbol ">&pound;</span>
                                                        </div>
                                                        <div class="col-sm-18">
                                                            <input class="legal_fee form-control" type="text" name="legal_fee[]" value="{{ $feeStructure->legal_fee }}" placeholder="Enter Value" >
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
                                    @endif
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
                <!--start of Sale & Purchase -->
                <div id="salePurchase" class="fee-section hidden panel col-sm-23">
                    <div class="panel-body">
                        <div class="full col-sm-23">
                            <div class="row">
                                <div id="feeScaleHeadingsContainer" class="col-sm-7">
                                    <h6 class="salePurchase"><strong>Sale &amp; Purchase Fee Scale</strong></h6>
                                </div>
                                <table class="table nohighlight">
                                    <tbody id="tbodySalePurchase">
                                    <tr class="duplicate row">
                                        <td class="col-sm-1"></td>
                                        <td class="col-sm-5">
                                            <div class="row">
                                                <div class="col-sm-8">
                                                    <span><strong>From</strong></span>
                                                    <span class="pound-symbol ">&pound;</span>
                                                </div>
                                                <div class="col-sm-16">
                                                    <input readonly class="price_from form-control" type="text" name="price_from_sale_purchase[]" value="0.00">
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
                                                    <input class="price_to form-control" type="text" name="price_to_sale_purchase[]" placeholder="Enter Value" >
                                                </div>
                                            </div>
                                        </td>
                                        <td class="col-sm-1"></td>
                                        <td class="col-sm-4">
                                            <p class="pt-2"><input class="unlimited-checkbox unlimited-checkbox-sale-purchase" type="checkbox"/>Unlimited</p>
                                        </td>
                                        <td class="col-sm-1"></td>
                                        <td class="col-sm-5">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <span><strong>Fee</strong></span>
                                                    <span class="pound-symbol ">&pound;</span>
                                                </div>
                                                <div class="col-sm-18">
                                                    <input class="legal_fee form-control" type="text" name="legal_fee_sale_purchase[]" placeholder="Enter Value" >
                                                </div>
                                                <div class="hidden">
                                                    <input class="form-control case_type_sale_purchase" name="case_type_sale_purchase" type="text" placeholder="Enter Value">
                                                </div>
                                            </div>
                                        </td>
                                        <td class="col-sm-1">
                                            <p class="text-center pt-2 delete-btn">
                                                <a href=""><i class="far fa-trash"></i></a>
                                            </p>
                                        </td>
                                    </tr>
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
                <!--end of Sale & Purchase -->
                <div class="clean-panel col-sm-24" style="background:none;border:none;box-shadow:none;">
                    <div class="row">
                        <div class="col-sm-7">
                            <a href="/solicitors/office/{{ $solicitorOffice->slug }}">
                                <button class="cancel-button col-sm-23" type="button">Cancel</button>
                            </a>
                        </div>
                        <div class="col-sm-7">
                            <button class="success-button col-sm-23">Save</button>
                        </div>
                        <div class="col-sm-10"></div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @push('scripts')
        <script type="text/javascript" src="/js/solicitor/office/fee-structure.js"></script>
    @endpush
@endsection
