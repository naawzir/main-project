@extends('mail-partials.mail-header', ['title' => $title])
@section('content')
    <h2>RE: {{ $caseref }} {{ $address }}</h2>
    <p>An update request has been made by {{ $user }}.</p>
    <p>Please can you either update the milestones or add a message through the message function within the next 4 hours.</p>
    <p>This will then be passed on to the agent and will remove the need for them to call you for an update.</p>
    <p>{{ $messagebody }}</p>
    <p>Thanks</p>
    <p><strong>The Conveyancing Partnership</strong></p>
@endsection
@include('mail-partials.mail-footer')
