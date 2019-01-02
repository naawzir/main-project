@extends('fake-users._layout')

@section('content')
    <h1>Login as an existing user</h1>

    <form action="{{ route('fake-users.activate') }}" method="post">
        {{ csrf_field() }}

        <div class="form-group">
            <label for="user">User</label>
            <select name="user" id="user" class="form-control" required>
                <option value="" selected disabled>Select a user&hellip;</option>
                @foreach(\App\User::all() as $user)
                    <option value="{{ $user->id }}" @if(old('user') === $user->id || $role === $user->username) selected @endif>
                        {{ $user->forenames }} {{ $user->surname }} - {{ $user->username }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-success">Login</button>
        </div>
    </form>
@endsection
