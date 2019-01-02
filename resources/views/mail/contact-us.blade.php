@extends('mail-partials.mail-header', ['title' => $title])
@section('content')
    <p>A message has been received from {{ $username }} at {{$branch}}.</p>
    <p>{{ $messagebody }}</p>
    <p>Thanks</p>
    <p><strong>The Conveyancing Partnership</strong></p>
@endsection
@include('mail-partials.mail-footer')
