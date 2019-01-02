@extends('layouts.app')
@section('content')

    <div class="col-sm-20 window">

        @include('layouts.breadcrumb',
            ['breadcrumbs' =>
                [
                    'solicitors' => 'Solicitors',
                    'edit' => 'Edit',
                    $solicitor->slug => $solicitor->name
                ]
            ]
        );

        <div id="page-header" class="col-sm-24">
            <h1>Edit Solicitor: {{ $solicitor->name }}</h1>
        </div>

        <form id="edit-solicitor" action="/solicitors/{{ $solicitor->slug }}/edit" method="post" enctype="multipart/form-data">

            {{csrf_field()}}

            <div id="content-area" class="panel col-sm-23">

                @include('solicitor._form')

                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-8">

                            <div class="col-sm-20 ml-5">
                                <div class="form-group">
                                    <label for="name">Solicitor</label>
                                    <input type="text" class="form-control" value="{{ $solicitor->name }}" id="name" name="name" required>
                                </div>
                                @if ($solicitorOffice->status === 'Active' || $solicitorOffice->status === 'Inactive')
                                <div class="form-group">
                                    <label for="status">Solicitor Status</label>
                                    <select id="status" name="status" class="form-control" required>
                                        <option @if ($solicitorOffice->status === 'Active') selected @endif value="Active">Active</option>
                                        <option @if ($solicitorOffice->status === 'Inactive') selected @endif value="Inactive">Inactive</option>
                                    </select>
                                </div>
                                @endif
                                <div class="form-group">
                                    <label for="office_name">Solicitor Office Name (default)</label>
                                    <input type="text" class="form-control" value="{{ $solicitorOffice->office_name }}" id="office_name" name="office_name" required>
                                </div>

                                <div id="solicitorOfficeAddress"><strong>Current Address:</strong>
                                    <p> {{ $solicitorOffice->address->getAddress() }}</p>
                                </div>

                                <div class="form-group">
                                    <label for="postcode" id="address-label"><strong>Address</strong></label>
                                    <div class="row">
                                        <input type="text" pattern="[a-zA-Z0-9 ]+" placeholder="Postcode Search*" class="col-sm-20 form-control" id="postcode" name="postcode" value="{{ old('postcode', $solicitorAddress->postcode) }}" required>
                                        <button type="button" class="col-sm-3" id="postcodeLookupBtn"><i class="fas fa-search"></i></button>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <select id="address-list" class="form-control"></select>
                                    <a href="" id="enter-address">Or Enter Address Manually</a>
                                </div>

                                <div id="manual-address" class="hidden">

                                    <div class="form-group">
                                        <label for="building_name"><strong>Building Name</strong></label>
                                        <input type="text" class="form-control" placeholder="Building Name" id="building_name" name="building_name" value="{{ old('building_name', $solicitorAddress->building_name) }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="building_number"><strong>Building Number</strong></label>
                                        <input type="text" class="form-control" placeholder="Building Number" id="building_number" name="building_number" value="{{ old('building_number', $solicitorAddress->building_number) }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="address_line_1"><strong>Address Line 1</strong></label>
                                        <input type="text" class="form-control" placeholder="Address Line 1" id="address_line_1" name="address_line_1" value="{{ old('address_line_1', $solicitorAddress->address_line_1) }}" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="address_line_2"><strong>Address Line 2</strong></label>
                                        <input type="text" class="form-control" placeholder="Address Line 2" id="address_line_2" name="address_line_2" value="{{ old('address_line_2', $solicitorAddress->address_line_2) }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="town"><strong>Town</strong></label>
                                        <input type="text" placeholder="City/Town" class="form-control" id="town" name="town" value="{{ old('town', $solicitorAddress->town) }}" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="county"><strong>County</strong></label>
                                        <input type="text" placeholder="County" class="form-control" id="county" name="county" value="{{ old('county', $solicitorAddress->county) }}" required>
                                    </div>

                                </div>

                            </div>

                        </div>

                        <div class="col-sm-8">
                            <div class="col-sm-22">

                                <div class="form-group">
                                    <label for="phone"><strong>Phone</strong></label>
                                    <input type="text" placeholder="Phone" class="form-control" value="{{ old('phone', $solicitorOffice->phone) }}" id="phone" name="phone" required>
                                </div>

                                <div class="form-group">
                                    <label for="email"><strong>Email</strong></label>
                                    <input type="email" placeholder="Office Email" class="form-control" value="{{ old('email', $solicitorOffice->email) }}" id="email" name="email" required>
                                </div>

                                <div class="form-group">
                                    <label for="contract_signed"><strong>Contract Signed</strong></label>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" @if ($solicitor->contract_signed) checked="checked" @endif name="contract_signed" id="contract_signed" value="1">
                                        <label class="form-check-label" for="contract_signed">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" @if (!$solicitor->contract_signed) checked="checked" @endif name="contract_signed" id="contract_not_signed" value="0">
                                        <label class="form-check-label" for="contract_not_signed">No</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-8">
                            <div class="col-sm-22">
                                <div class="form-group">
                                    <label for="capacity"><strong>Set Capacity</strong></label>
                                    <input type="text" placeholder="Enter Capacity" class="form-control" value="{{ old('capacity', $solicitorOffice->capacity) }}" id="capacity" name="capacity" required>
                                </div>

                                <div class="form-group">
                                    <label for="tm_ref"><strong>TM Ref</strong></label>
                                    <input type="text" placeholder="TM Ref" class="form-control" value="{{ old('tm_ref', $solicitorOffice->tm_ref) }}" id="tm_ref" name="tm_ref">
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

            </div>

            <div class="clean-panel col-sm-24" style="background:none;border:none;box-shadow:none;">
                <div class="row">
                    <div class="col-sm-12"></div>
                    <div class="col-sm-6">
                        <a href="/solicitors/{{ $solicitor->slug }}/edit">
                            <button class="cancel-button col-sm-21" type="button">Cancel</button>
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
