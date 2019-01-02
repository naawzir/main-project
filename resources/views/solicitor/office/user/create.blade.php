@extends('layouts.app')
@section('content')

    <div class="col-sm-20 window">

        @include('layouts.breadcrumb',
            ['breadcrumbs' =>
                [
                    'solicitors' => 'Solicitors',
                    'create' => 'Create',
                    'users' => 'Users',
                    $solicitorOffice->slug => $solicitor->name . ' (' . trim($solicitorOffice->office_name) . ')'
                ]
            ]
        )

        <div id="page-header" class="col-sm-23">
            <h1>{{ $solicitor->name }}</h1>
            <h6>({{ trim($solicitorOffice->office_name) }})</h6>
        </div>

        <div id="content-area">

            @include('solicitor.office.office-details')

            <div id="solicitorUserAdded" class="hidden clean-panel col-sm-23"></div>

            <form id="solicitorStaffForm" action="/solicitors/office/{{ $solicitorOffice->slug }}/user/create" method="post" enctype="multipart/form-data">

                {{csrf_field()}}

                <div class="panel col-sm-23">

                    <div id="successMessage" class="hidden col-sm-22 success-box ml-5"></div>
                    <div id="errorMessage" class="hidden col-sm-23 alert-box warning ml-4"><p class="ml-3">All fields are required.</p></div>

                    <h3>Add Solicitor Staff</h3>
                    <div class="panel-body">
                        <div class="full col-sm-23">

                            <div class="row duplicate" style="border-bottom:1px solid #cdcdcd;">

                                <div id="errorMessages_1" class="error-messages hidden col-sm-23 alert-box warning ml-5"><p class="ml-3"></p></div>

                                <div class="col-sm-12">
                                    <div class="col-sm-20 ml-5">
                                        <div class="form-group">
                                            <label for="title"><strong>Title</strong></label>
                                            <select id="title" name="title[]" class="form-control" required>
                                                <option value="">Please select</option>
                                                @foreach(config('app.usertitles') as $key => $title)
                                                    <option value="{{ $key }}" @if (old('title') === $key) selected="selected" @endif> {{ $title }} </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="forenames"><strong>Forename</strong></label>
                                            <input id="forenames" class="form-control forenames" type="text" name="forenames[]" placeholder="Forename" value="{{ old('forenames')  }}" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="surname"><strong>Surname</strong></label>
                                            <input id="surname" class="form-control" type="text" name="surname[]" placeholder="Surname" value="{{ old('surname')  }}" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="col-sm-22">
                                        <div class="form-group">
                                            <label for="phone"><strong>Phone</strong></label>
                                            <input id="phone" class="form-control" type="text" name="phone[]" placeholder="Phone" value="{{ old('phone')  }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="mobile"><strong>Mobile / Additional Phone</strong></label>
                                            <input id="phone_other" class="form-control" type="text" name="phone_other[]" placeholder="Mobile" value="{{ old('phone_other')  }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="email"><strong>Email</strong></label>
                                            <input id="email" class="form-control email" data-id="1" type="email" name="email[]" placeholder="Email" value="{{ old('email')  }}" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <a class="ml-5 mb-4" href="" id="addSolicitorStaff">Add Solicitor Staff</a>
                        </div>
                    </div>

                </div>

                <div class="clean-panel col-sm-23" style="background:none;border:none;box-shadow:none;">
                    <div class="row">
                        <div class="col-sm-12"></div>
                        <div class="col-sm-6">
                            <a href="/solicitors/office/{{ $solicitorOffice->slug }}">
                                <button class="cancel-button col-sm-23" type="button">Cancel</button>
                            </a>
                        </div>
                        <div class="col-sm-6">
                            <a href="{{ $solicitorOffice->status === 'Onboarding' && !$solicitorOffice->feeStructure ? '/solicitors/office/' . $solicitorOffice->slug . '/additional-fees/create/' : '/solicitors/office/' . $solicitorOffice->slug }}">
                                <button class="success-button col-sm-24" id="saveStaffDetails">Save</button>
                            </a>
                        </div>
                    </div>
                </div>

            </form>
        </div>

        @push('scripts')
            <script type="text/javascript">
                $(document).ready(function() {

                    $('#addSolicitorStaff').on('click', function(e) {
                        e.preventDefault();

                        $('.duplicate:last')
                            .clone()
                            .find("input")
                            .val("")
                            .end()
                            .appendTo('.full');

                        const num = parseInt($(".error-messages:last").attr('id').match(/\d+/g), 10) + 1;
                        $("#solicitorStaffForm").find(".error-messages:last").attr("id", "errorMessages_" + num).hide();
                        $("#solicitorStaffForm").find(".email:last").attr("data-id", num);

                    });

                    $("#saveStaffDetails").click(function(e) {
                        e.preventDefault();

                        $(".error-messages").hide();

                        var emptyField = null;
                        $("input").each(function() {
                            if($(this).val() === '') {
                                emptyField = true;
                            }
                        });

                        if($("select").val() === '') {
                            emptyField = true;
                        }

                        if(emptyField) {
                            $("#errorMessage").show();
                            return false;
                        }

                        const emails = [];
                        $(".email").each(function() {
                            emails.push($(this).val());
                        });

                        const data = {
                            emails : emails
                        };

                        const ajaxRequest = tcp.xhr.get('/solicitors/office/user/check-email', data);

                        ajaxRequest.done(function(data) {
                            $("#solicitorStaffForm").submit();
                        });

                        ajaxRequest.fail(function(error) {
                            Object.keys(error).forEach(function(key) {
                                $("#errorMessages_" + key + " p").text(error[key]);
                                $("#errorMessages_" + key).show();
                            });
                        });
                    });

                    function checkForDuplicateEmail() {
                        var arr = [];
                        $(".email").each(function() {
                            var dataId = $(this).data("id");
                            var value = $(this).val();

                            if(value !='') {
                                if (arr.indexOf(value) == -1) {
                                    arr.push(value);
                                    $(".error-messages").hide();
                                    //console.log('different: ' + value);
                                } else {
                                    //console.log('same: ' + value);
                                    $(".error-messages").hide();
                                    $("#errorMessages_" + dataId + " p").text('Duplicate email address entered. Please enter another.');
                                    $("#errorMessages_" + dataId).show();
                                }
                            }
                        });
                    }

                    $('#solicitorStaffForm').on('keyup', '.email', function() {
                        checkForDuplicateEmail($(this).val());
                    });

                    $('#solicitorStaffForm').on('blur', '.email', function() {
                        checkForDuplicateEmail($(this).val());
                    });
                });
            </script>
    @endpush

@endsection
