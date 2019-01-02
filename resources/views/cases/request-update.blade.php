@extends('layouts.app')
@section('content')

    <div class="col-sm-20 window">

        @include('layouts.breadcrumb',
            [
                'breadcrumbs' =>
                [
                    'cases' => 'Cases',
                    'request-update' => 'Request Update',
                    $case->slug => $case->reference . ': ' . $address->building_number . ' ' . $address->address_line_1
                ]
            ]
        )

        <div id="page-header" class="col-sm-24">
            <h1>Request Update on case {{$case->reference}} for {{$address->building_number . ' ' . $address->address_line_1}}</h1>
        </div>

        <form id="request-update" action="/cases/{{$case->slug}}/request-update/" method="post" enctype="multipart/form-data">

            {{csrf_field()}}

            <div id="content-area" class="panel col-sm-23">
                <div class="panel-body">
                    <div class="col-sm-22 ml-5">

                        <div class="form-group">
                            <label for="recipient">Recipient</label>
                            <select required id="recipient" name="recipient" class="form-control">
                                <option>Please Select</option>
                                <option value="{{$solicitorOffice->email . ';' . $pmemail->email}}">Solicitor: {{$solicitor->name}} ({{$solicitorOffice->office_name}})</option>
                                <option value="{{$pmemail->email}}">Panel Manager</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="message">Message</label>
                            <textarea required class="form-control" name="message" id="message" rows="8" placeholder="Please provide us with an update on this case."></textarea>
                        </div>

                        <!-- hidden fields -->
                        <input type="hidden" name="caseref" value="{{$case->reference}}">
                        <input type="hidden" name="address" value="{{$address->building_number . ' ' . $address->address_line_1}}">
                        <input type="hidden" name="user" value="{{$user->forenames . ' ' . $user->surname}}">
                        <input type="hidden" name="from" value="enquiries@tcp-ltd.co.uk">

                    </div>
                </div>
            </div>
            <div class="clean-panel col-sm-24" style="background:none;border:none;box-shadow:none;">
                <div class="row">
                    <div class="col-sm-12"></div>
                    <div class="col-sm-6">
                        <a href="/cases">
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
            
        });
    </script>
@endpush
@endsection
