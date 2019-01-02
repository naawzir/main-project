@extends('layouts.app')
@section('content')

<div class="col-sm-2 col-md-4 col-lg-10 columns"></div>

<div class="col-sm-20 col-md-16 col-lg-4 columns login-panel">
   
   <img class="image" src="{{ asset('img/TCP_login.png') }}" />

    <P>Create Password</P>
    
    <form role="form" method="POST" action="{{ route('password.create') }}">
        
        {{ csrf_field() }}

        <div class="form-group">

            <input id="login" type="text" class="form-control" name="login" placeholder="EMAIL OR USERNAME" value="{{ old('login') }}" required>

        </div>


        <div class="form-group">
                
            <input id="password" type="password" class="form-control" name="password" placeholder="PASSWORD" required>

        </div>


        <div class="form-group">
            
            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="CONFIRM PASSWORD" required>

        </div>


        <div class="form-group">

            <button type="submit" class="button action-btn">CREATE PASSWORD</button>

        </div>

    </form>

</div>

<div class="col-sm-2 col-md-4 col-lg-10 columns"></div>

@endsection