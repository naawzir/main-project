@extends('layouts.app')
@section('content')

<div class="col-sm-2 col-md-4 col-lg-10 columns"></div>

<div class="col-sm-20 col-md-16 col-lg-4 columns login-panel">

    <img class="image" src="{{ asset('img/TCP_login.png') }}" />

    <p>Activate Account</p>

    <form role="form" method="POST" disabled>

        <p class="alert alert-info">Account activation has been disabled.</p>
        {{ csrf_field() }}

        <input type="hidden" name="token" value="{{ $token }}">

        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">

            <input class="text-input" type="text" name="email" value="{{ $user->email or old('email') }}">
            
            @if ($errors->has('email'))
                <span>
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
            @endif

        </div>

        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                
            <input id="password" type="password" class="form-control" name="password" placeholder="NEW PASSWORD" required>

            @if ($errors->has('password'))
                <span>
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
            @endif
        </div>

        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
            
            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="CONFIRM NEW PASSWORD" required>

            @if ($errors->has('password_confirmation'))
                <span>
                    <strong>{{ $errors->first('password_confirmation') }}</strong>
                </span>
            @endif
        </div>

        <div class="form-group">

            <button type="submit" class="button action-btn">RESET PASSWORD</button>

        </div>

    </form>

</div>

<div class="col-sm-2 col-md-4 col-lg-10 columns"></div>

@endsection