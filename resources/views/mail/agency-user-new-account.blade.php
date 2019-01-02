@extends('mail-partials.mail-header', array('title' => $title))

@section('content')

<h2>Hello {{ $user['forenames'] }}</h2>

<p>You have received this email as you have been added to the TCP system.</p>

<p>Please continue by following the link below:</p>

<a href="{{ $url }}">Login</a>

<p>Thanks</p>

<p><strong>The Conveyancing Partnership</strong></p>

@endsection

@include('mail-partials.mail-footer')