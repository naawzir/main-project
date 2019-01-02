@extends('layouts.app')
@section('content')

    <div class="col-sm-2 col-md-4 col-lg-10 columns"></div>

    <div class="col-sm-20 col-md-16 col-lg-4 columns login-panel">
        
        <img class="image" src="{{ asset('img/TCP_login.png') }}" />

        <p>Solicitor Login Page</p>

        <form role="form" method="POST" action=" {{ url('login') }}">

            {{ csrf_field() }}

            <div class="form-group">

                <input class="text-input" type="text" name="login" value="{{ old('login') }}" placeholder="EMAIL OR USERNAME">

            </div>

            <div class="form-group">

                <input class="text-input" type="password" name="password" placeholder="PASSWORD">

            </div>

            <div class="form-group">

                <input type="submit" class="button action-btn" value="Sign In"><br/>

            </div>

        </form>

    </div>

    <div class="col-sm-2 col-md-4 col-lg-10 columns"></div>

@endsection