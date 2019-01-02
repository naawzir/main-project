@extends('layouts.app')
@section('content')
    <div class="col-sm-20 window">

        @include('layouts.breadcrumb', [
            "breadcrumbs" =>
                [
                    "solicitors" => "Solicitors",
                    "fees" => "Fees",
                    $solicitor->slug => $solicitor->name . ' (' . trim($solicitorOffice->office_name) . ')'
                ]
            ]
        )

        <div id="page-header" class="col-sm-23">
            <h1>{{ $solicitor->name }}</h1>
            <h6>({{ trim($solicitorOffice->office_name) }})</h6>
        </div>

        <div id="content-area">

            @include('solicitor.office.office-details')

            <form id="add-solicitor-fees" action="/solicitors/office/{{ $solicitorOffice->slug }}/additional-fees/create/" method="post" enctype="multipart/form-data">

                {{csrf_field()}}

                <div class="panel col-sm-23">
                    <h3>Set Additional Fees</h3>
                    <div class="panel-body ml-5">
                        <div class="full col-sm-23">

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <label for="mortgage"><strong>New Mortgage Fee</strong></label>
                                    </div>
                                    <div class="col-sm-1 mr-2">
                                        <span class="float-right pound-symbol">&pound;</span>
                                    </div>
                                    <div class="col-sm-3">
                                        <input id="mortgage" class="form-control" type="text" name="mortgage" placeholder="Enter Value" value="{{ old('mortgage', !empty($priOfficeAddFees->mortgage) ? $priOfficeAddFees->mortgage : '') }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <label for="mortgage_redemption"><strong>Mortgage Redemption Fee</strong></label>
                                    </div>
                                    <div class="col-sm-1 mr-2">
                                        <span class="float-right pound-symbol">&pound;</span>
                                    </div>
                                    <div class="col-sm-3">
                                        <input id="mortgage_redemption" class="form-control" type="text" name="mortgage_redemption" placeholder="Enter Value" value="{{ old('mortgage_redemption', !empty($priOfficeAddFees->mortgage_redemption) ? $priOfficeAddFees->mortgage_redemption : '') }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <label for="leasehold"><strong>Leasehold</strong></label>
                                    </div>
                                    <div class="col-sm-1 mr-2">
                                        <span class="float-right pound-symbol">&pound;</span>
                                    </div>
                                    <div class="col-sm-3">
                                        <input id="leasehold" class="form-control" type="text" name="leasehold" placeholder="Enter Value" value="{{ old('leasehold', !empty($priOfficeAddFees->leasehold) ? $priOfficeAddFees->leasehold : '') }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <label for="archive"><strong>Archive Fee</strong></label>
                                    </div>
                                    <div class="col-sm-1 mr-2">
                                        <span class="float-right pound-symbol">&pound;</span>
                                    </div>
                                    <div class="col-sm-3">
                                        <input id="archive" class="form-control" type="text" name="archive" placeholder="Enter Value" value="{{ old('archive', !empty($priOfficeAddFees->archive) ? $priOfficeAddFees->archive : '') }}" required>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>

                </div>

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

@endsection
