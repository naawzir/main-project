@extends('mail-partials.mail-header', array('title' => $title))

@section('content')

<h2>Hello {{ $user['forenames'] }}</h2>

<p>You have received this email as you have <strong>deactivated</strong> your TCP account.</p>

<p>You will now no longer be able to log in to our system, and after a period of time in keeping with our data recording your details will be removed in their entirity.</p>

<p>If this was done innacurately, or should you wish to re-activate your account in future, Please contact The Conveyancing Department on: {{ config('app.tel') }}</p>

<p>Thanks</p>

<p><strong>The Conveyancing Partnership</strong></p>

@endsection

@include('mail-partials.mail-footer')