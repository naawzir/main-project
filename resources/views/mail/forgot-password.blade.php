@extends('mail-partials.mail-header', array('title' => $title))

@section('content')

<p><strong>Hello {{$user['forenames']}},</strong></p>

<p>You have received this email as a request has been made to <strong>reset the password</strong> on your account. If this was you, please continue by following the link below:</p>

<button class="biginput success-button"><a href="{{ $url }}">Reset Password</a></button>

<p>Thanks</p>

<p><strong>The Conveyancing Partnership</strong></p>

@endsection

@include('mail-partials.mail-footer')