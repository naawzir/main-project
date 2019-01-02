@extends('layouts.app')
@section('content')

    @push('styles')
        <link href="{{ asset('css/add-case.css') }}" rel="stylesheet">
    @endpush

    <div class="medium-4 large-3"></div>

    <div class="medium-6 login-panel">

        <h4></h4>

        <span>Edit User Role</span>

        <form role="form" method="POST" action="/userroles/{{ $userRole->id }}" enctype="multipart/form-data">

            {{ csrf_field() }}

            <input type="hidden" class="form-control" id="userRoleIdEncrypted" name="userRoleIdEncrypted" value="{{ Crypt::encrypt($userRole->id) }}">

            <div class="form-group">

                <input type="text" class="form-control" id="title" name="title" value="{{ $userRole->title, old('title') }}" placeholder="Please enter title *">

            </div>

            {{--@if($userRole->id !== 1)--}}

                <div class="form-group">

                    <label>Permissions</label>

                    <br>

                    <select id='basePermissions' multiple='multiple' name='base_permissions[]'>

                        @foreach ($selectedPermissions as $selectedPermission)

                            <option value="{{ $selectedPermission->id }}" selected>{{ $selectedPermission->name }}</option>

                        @endforeach

                        @foreach ($notSelectedPermissions as $notSelectedPermission)

                            <option value="{{ $notSelectedPermission->id }}">{{ $notSelectedPermission->name }}</option>

                        @endforeach

                    </select>

                </div>

            {{--@endif--}}
            <br>

            <div class="form-group">

                <input type="text" class="form-control" id="description" name="description" value="{{ $userRole->description, old('description') }}" placeholder="Please enter description *">

            </div>

            <div class="form-group">

                <select name="super_user" id="super_user" class="form-control" >

                    <option value="1" @if ($userRole->super_user === 1) selected="selected" @endif>Super User</option>
                    <option value="0" @if ($userRole->super_user === 0) selected="selected" @endif>Not a Super User</option>

                </select>

            </div>

            <div class="form-group">

                <select name="active" id="active" class="form-control" >

                    <option value="1" @if ($userRole->active === 1) selected="selected" @endif>Active</option>
                    <option value="0" @if ($userRole->active === 0) selected="selected" @endif>Inactive</option>

                </select>

            </div>

            <div class="form-group">

                <button type="submit" class="button">Save</button>

            </div>

        </form>

        <div id="savedMessage">

            <p>Successfully saved<br><i class="fa fa-check" style="font-size:39px;" aria-hidden="true"></i></p>

        </div>

        <div id="failedToSaveMessage">

            <p>Failed to save<br><i class="fa fa-times" aria-hidden="true" style="font-size:39px;"></i></p>

        </div>

    </div>

    <div class="medium-4 large-5"></div>


    @push('scripts')

        <script>

            $(document).ready(function() {

                $("#basePermissions").multiSelect();

                $("#basePermissions").change(function() {

                    var userRoleIdEncrypted = $("#userRoleIdEncrypted").val();
                    var basePermissions = $("#basePermissions").val();
                    var token = $('input[name=_token]').val();

                    $.ajax({
                        type: "POST",
                        url: "/admin/userroles/permissions",
                        data: {
                            'userRoleIdEncrypted' : userRoleIdEncrypted,
                            'base_permissions' : basePermissions,
                            '_token' : token
                        },
                        //dataType: "json",
                        success: function (data) {
                            $("#savedMessage").fadeIn().fadeOut(5000);
                        },
                        error: function (data) {
                            $("#failedToSaveMessage").fadeIn().fadeOut(5000);
                        }
                    });
                });

            });

        </script>

    @endpush

@endsection