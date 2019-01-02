@extends('mail-partials.mail-header', ['title' => $title])

    @section('content')

        <p><strong>Hello {{$user['forenames']}},</strong></p>

        <p>The following note has been added by {{ $accountManager }} at {{ $date }}:</p>

        <p>$note</p>

        <p><strong>The Conveyancing Partnership</strong></p>

    @endsection

@include('mail-partials.mail-footer')