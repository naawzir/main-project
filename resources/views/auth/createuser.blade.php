@extends('layouts.app')
@section('content')


    <div class="medium-4 large-3"></div>

    <div class="medium-6 login-panel">

        <h4>You're logged in as a <?php echo $agent->position; ?></h4>
        <p id="userLoggedInPosition" style="display:none;"><?php echo $agent->position; ?></p>

        <span>Create Agency Staff</span>

        <form role="form" method="POST" action="/agency/createuser" enctype="multipart/form-data">

            {{ csrf_field() }}

            <div class="form-group">

                <select name="position" id="position" class="form-control" >
                    <option value="">Please select Position *</option>
                    @if(checkIfUserCanSeeThis('Create Business Owner'))
                        <option value="Business Owner" @if (old('position') === 'Business Owner') selected="selected" @endif >Business Owner</option>
                    @endif
                    <option value="Branch Manager" @if (old('position') === 'Branch Manager') selected="selected" @endif >Branch Manager</option>
                    <option value="Agent" @if (old('position') === 'Agent') selected="selected" @endif >Agent</option>
                </select>

            </div>

            <div class="form-group" style="display:none;" id="branches">

                <select name="branch" id="branch" class="form-control" >
                    <option value="">Please select Branch *</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id  }}" @if (old('branch') == $branch->id) selected="selected" @endif > {{ $branch->name }} </option>
                    @endforeach
                </select>

            </div>

            <div class="form-group">

                <select name="title" id="title" class="form-control" >
                    <option value="">Please select Title</option>
                    @foreach(config('app.usertitles') as $key => $title)
                        <option value="{{ $key }}" @if (old('title') === $key) selected="selected" @endif> {{ $title }} </option>
                    @endforeach
                </select>

            </div>

            <div class="form-group">
                <input type="text" class="form-control" id="forenames" name="forenames" value="{{ old('forenames') }}" placeholder="Please enter forename/s *">
            </div>

            <div class="form-group">
                <input type="text" class="form-control" id="surname" name="surname" value="{{ old('surname') }}" placeholder="Please enter surname *">
            </div>

            <div class="form-group">
                <input type="text" class="form-control" id="username" name="username" value="{{ old('username') }}" placeholder="Please enter username *">
            </div>

            <div class="form-group">
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="Please enter email">
            </div>

            <div class="form-group">
                <input type="text" class="form-control" id="mobile" name="mobile" value="{{ old('mobile') }}" placeholder="Please enter mobile">
            </div>

            <div class="form-group">
                <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone') }}" placeholder="Please enter phone">
            </div>

            <div class="form-group">
                <input type="text" class="form-control" id="phone_other" name="phone_other" value="{{ old('phone_other') }}" placeholder="Please enter other phone">
            </div>

{{--            <div class="form-group">
                <select name="active" id="active" class="form-control" >
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>--}}

            <div class="form-group">

                <button type="submit" class="button">Save</button>

            </div>

        </form>

    </div>

    <div class="medium-4 large-5"></div>

@endsection