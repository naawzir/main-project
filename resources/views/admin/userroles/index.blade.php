@extends('layouts.app')
@section('content')

        <div class="window medium-auto large-auto">

            <div class="row common-con" id="newsList">

                <div class="col-xs-12">

                    <div class="panel panel-midnightblue">

                        <div class="panel-heading">

                            <h4>USER ROLES LIST</h4>

                        </div>

                        <div class="panel-body">

                            <div class="options">

                                   {{--@if(checkIfUserCanSeeThis('/userroles/create'))

                                    <div class="btn-toolbar">

                                        <a href="/admin/userroles/create" class="btn btn-default">
                                            <i class="fa fa-table"></i> Create User Role
                                        </a>

                                    </div>

                                @endif--}}

                                <div id="additionalFieldsBtns" class="hidden">

                                    <button style="margin-top:0px;" class="button" id="updateBtn">Update</button>
                                    <button style="margin-top:0px;" class="button" id="closeBtn">Close</button>

                                </div>

                                <div id="additionalFields" class="hidden">


                                    <form action="" method="POST">

                                            {{ csrf_field() }}

                                        <table id="additionalFieldsTbl">

                                            <thead>

                                            <tr>

                                                <th>BASE PERMISSIONS</th>

                                            </tr>

                                            </thead>

                                            <tbody>

                                                <tr>

                                                    <td data-field="base_permissions" id="basePermissionTd"></td>

                                                </tr>

                                            </tbody>

                                        </table>

                                    </form>

                                </div>

                            </div>

                            <div class="col-xs-12 deleteinfo_notify">

                            </div>

                            <div class="row">

                                <div class="alert-success"></div>

                            </div>


                            <div id="createRecordBtns">

                                <button style="margin-top:0px;" class="button" id="createBtn">Create</button>
                                <button style="margin-top:0px;" class="button hidden" id="removeBtn">Remove</button>
                                <button style="margin-top:0px;" class="button hidden" id="saveBtn">Save</button>

                            </div>

                            <form action="" method="POST">

                                {{ csrf_field() }}

                                <table id="createRecord" class="hidden">

                                    <thead>

                                    <tr>

                                        <th style="width:18%;">TITLE</th>
                                        <th style="width:22%;">DESCRIPTION</th>
                                        <th style="width:12%;">SUPER USER</th>
                                        <th>ACTIVE</th>
                                        <th>BASE PERMISSIONS</th>

                                    </tr>

                                    </thead>

                                    <tbody>

                                    <tr>

                                        <td data-field="title">

                                            <input placeholder="Title" id="title" type="text" name="title" required class="required" />

                                            <span class="hidden"></span>

                                        </td>

                                        <td data-field="description">

                                            <input placeholder="Description" id="description" type="text" name="description" required class="required" />

                                            <span class="hidden"></span>

                                        </td>

                                        <td data-field="super_user">

                                            <select name="super_user" id="superUser" required class="required">

                                                <option value="0">No</option>
                                                <option value="1">Yes</option>

                                            </select>

                                            <span class="hidden"></span>

                                        </td>

                                        <td data-field="active">

                                            <select name="active" id="active" required class="required">

                                                <option value="1">Active</option>
                                                <option value="0">Not Active</option>

                                            </select>

                                            <span class="hidden"></span>

                                        </td>

                                        <td>

                                            <select id='createBasePermissions' multiple='multiple' name='base_permissions[]'>

                                                @foreach($permissions as $permission)

                                                    <option value="{{ $permission->id }}">{{ $permission->name }}</option>

                                                @endforeach

                                            </select>

                                        </td>

                                    </tbody>

                                </table>

                            </form>


                            <table id="userRoleTable">

                                <thead>

                                <tr>

                                    <th>NO</th>
                                    <th style="width:18%;">TITLE</th>
                                    <th style="width:22%;">DESCRIPTION</th>
                                    <th style="width:12%;">SUPER USER</th>
                                    <th>ACTIVE</th>
                                    <th>DATE CREATED</th>
                                    <th style='width:14%;text-align: center'>ACTION</th>

                                </tr>

                                </thead>

                                <tbody>

                                @foreach ($records as $userRole)

                                    <tr data-id="{{ Crypt::encrypt($userRole->id) }}">

                                        <td>{{ $userRole->id }}</td>

                                        <td class="table-cell" data-field="title">

                                            <input type="text" class="hidden" name="title" />

                                            <span>{{ $userRole->title }}</span>

                                        </td>

                                        <td class="table-cell" data-field="description">

                                            <input type="text" class="hidden" name="description" />

                                            <span>{{ $userRole->description }}</span>

                                        </td>

                                        <td class="table-cell" data-field="super_user">

                                            <select name="super_user" class="hidden">

                                                <option value="0">No</option>
                                                <option value="1">Yes</option>

                                            </select>

                                            <span>{{ $userRole->super_user === 1 ? 'Yes' : 'No' }}</span>

                                        </td>

                                        <td class="table-cell" data-field="active">

                                            <select name="active" class="hidden">

                                                <option value="1">Yes</option>
                                                <option value="0">No</option>

                                            </select>

                                            <span>{{ !empty(isActive($userRole)) ? 'Yes' : 'No' }}</span>

                                        </td>

                                        <td> {{ date('d/m/Y', strtotime($userRole->date_created)) }} </td>

                                        <td style="text-align: center">

                                            {{--@if ($userPermission->checkIfUserCanSeeThis('/users/{id}/destroy'))--}}
                                            <button style="margin-top:0px;" class="button additional" data-id-additional="{{ Crypt::encrypt($userRole->id) }}">ADDITIONAL</button>

                                            <!--<a href="/admin/userroles/{{ Crypt::encrypt($userRole->id) }}" class="delete" id="{{ $userRole->id }}" title="DELETE"> EDIT </a>-->

                                            <button style="margin-top:0px;" class="button">

                                                <!--<a href="/userroles/{{ Crypt::encrypt($userRole->id) }}/destroy" class="delete" data-id="{{ Crypt::encrypt($userRole->id) }}" title="DELETE"> DELETE </a>-->
                                                <a href="/userroles/{{ $userRole->id }}/destroy" class="delete" data-id="{{ Crypt::encrypt($userRole->id) }}" title="DELETE"> DELETE </a>

                                            </button>
                                            {{--@endif--}}

                                        </td>

                                    </tr>

                                @endforeach

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

            </div>

        </div>

@endsection