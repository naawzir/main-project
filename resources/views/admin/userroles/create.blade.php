@extends('layouts.app')
@section('content')

    <div class="medium-4 large-3"></div>

    <div class="medium-6 login-panel">

        <h4></h4>

        <span>Create User Role</span>

        <form role="form" method="POST" action="/admin/userroles/create" enctype="multipart/form-data">

            {{ csrf_field() }}

            <div class="form-group">
                <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" placeholder="Please enter title *">
            </div>

            <div class="form-group">
                <input type="text" class="form-control" id="description" name="description" value="{{ old('description') }}" placeholder="Please enter description *">
            </div>

            <div class="form-group">
                <select name="super_user" id="super_user" class="form-control" >
                    <option value="1" @if (old('super_user') === 1) selected="selected" @endif>Super User</option>
                    <option value="0" @if (old('super_user') === 0) selected="selected" @endif>Not a Super User</option>
                </select>
            </div>

            <div class="form-group">
                <select name="active" id="active" class="form-control" >
                    <option value="1" @if (old('active') === 1) selected="selected" @endif>Active</option>
                    <option value="0" @if (old('active') === 0) selected="selected" @endif>Inactive</option>
                </select>
            </div>

            <div class="form-group">
                <button type="submit" class="button">Save</button>
            </div>

        </form>

    </div>

    <div class="medium-4 large-5"></div>

@endsection