@extends('layouts.app')
@section('content')

    <div class="col-sm-20 window">

        @include('layouts.breadcrumb',
            ['breadcrumbs' =>
                [
                    'solicitors' => 'Solicitors',
                    $solicitor->slug . '/offices' => $solicitor->name,
                    'create' => 'Create',
                ]
            ]
        )

        <div id="page-header" class="col-sm-24">
            <h1>Add Solicitor Office: {{ $solicitor->name }}</h1>
        </div>

        <div id="content-container" class="row">

        </div>

        <form id="add-solicitor" action="/solicitors/{{ $solicitor->slug }}/office/create" method="post" enctype="multipart/form-data">

            {{csrf_field()}}

            <div id="content-area" class="panel col-sm-23">
                <div class="panel-body">

                    <div class="row">
                        <div class="col-sm-8">

                            <div class="col-sm-20 ml-5">

                                <div class="form-group">
                                    <label for="name"><strong>Solicitor</strong></label>
                                    <input type="text" disabled class="form-control" placeholder="Solicitor Name" value="{{$solicitor->name}}">
                                </div>

                                <div class="form-group">
                                    <label for="office_name"><strong>Solicitor Office Name</strong></label>
                                    <input type="text" class="form-control" placeholder="Solicitor Office Name" id="office_name" name="office_name" value="{{ old('office_name') }}" required>
                                </div>

                                <div class="form-group">
                                    <label for="postcode" id="address-label"><strong>Address</strong></label>
                                    <div class="row">
                                        <input type="text" minlength="5" maxlength="8" pattern="[a-zA-Z0-9 ]+" placeholder="Postcode Search*" class="col-sm-20 form-control" id="postcode" name="postcode" value="{{ old('postcode') }}" required>
                                        <button type="button" class="col-sm-3" id="postcodeLookupBtn"><i class="fas fa-search"></i></button>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <select id="address-list" class="form-control hidden"></select>
                                    <a href="" id="enter-address">Or Enter Address Manually</a>
                                </div>

                                <div id="manual-address" class="hidden">

                                    <div class="form-group">
                                        <label for="building_name"><strong>Building Name</strong></label>
                                        <input type="text" class="form-control" placeholder="Building Name" id="building_name" name="building_name" value="{{ old('building_name') }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="building_number"><strong>Building Number</strong></label>
                                        <input type="text" class="form-control" placeholder="Building Number" id="building_number" name="building_number" value="{{ old('building_number') }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="address_line_1"><strong>Address Line 1</strong></label>
                                        <input type="text" class="form-control" placeholder="Address Line 1" id="address_line_1" name="address_line_1" value="{{ old('address_line_1') }}" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="address_line_2"><strong>Address Line 2</strong></label>
                                        <input type="text" class="form-control" placeholder="Address Line 2" id="address_line_2" name="address_line_2" value="{{ old('address_line_2') }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="town"><strong>Town</strong></label>
                                        <input type="text" placeholder="City/Town" class="form-control" id="town" name="town" value="{{ old('town') }}" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="county"><strong>County</strong></label>
                                        <input type="text" placeholder="County" class="form-control" id="county" name="county" value="{{ old('county') }}" required>
                                    </div>

                                </div>
                            </div>

                        </div>

                        <div class="col-sm-8">
                            <div class="col-sm-22">

                                <div class="form-group">
                                    <label for="phone"><strong>Phone</strong></label>
                                    <input type="text" placeholder="Phone" class="form-control" id="phone" name="phone" value="{{ old('phone') }}" required>
                                </div>

                                <div class="form-group">
                                    <label for="email"><strong>Email</strong></label>
                                    <input type="email" placeholder="Office Email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                                </div>

                                <div class="form-group">
                                    <label for="referral_fee"><strong>Referral Fee</strong></label>
                                    <div class="row">
                                        <div class="col-sm-1">
                                            <span class="pound-symbol ">£</span>
                                        </div>
                                        <div class="col-sm-23">
                                            <input class="form-control" id="referral_fee" type="text" name="referral_fee" placeholder="Referral Fee" value="{{ old('referral_fee') }}" required>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="col-sm-8">
                            <div class="col-sm-22">

                                <div class="form-group">
                                    <label for="capacity"><strong>Set Capacity</strong></label>
                                    <input type="text" placeholder="Enter Capacity" class="form-control" id="capacity" name="capacity" value="{{ old('capacity') }}" required>
                                </div>

                                <div class="form-group">
                                    <label for="tmref"><strong>TM Ref</strong></label>
                                    <input type="text" placeholder="TM Ref" class="form-control" id="tm_ref" name="tm_ref" value="{{ old('tm_ref') }}">
                                </div>

                                <div class="form-group">
                                    <label for="image"><strong>Image</strong></label>
                                    <input type="file" class="form-control" id="image" name="image" value="{{ old('image') }}">
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
                        {{--<button class="cancel-button col-sm-22" type="button">Cancel</button>--}}
                        <a href="/solicitors">
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

    @push('scripts')
        <script type="text/javascript">
            $(document).ready(function()
            {
                $(".cancel-button").click(function() {
                    window.history.back();
                });
            });
        </script>
    @endpush

@endsection
