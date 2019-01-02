@extends('mail-partials.mail-header', ['title' => $title])
@section('content')
    <h2>In Branch Training</h2>
    <p>A training request has been made by {{ $username }}.</p>
    <p>Please can you arrange a training session for their branch {{$branch}} and contact them back</p>
    <p>{{ $messagebody }}</p>
    <p>Thanks</p>
    <p><strong>The Conveyancing Partnership</strong></p>
@endsection
@include('mail-partials.mail-footer')
