@extends('layouts.app')
@section('content')
<div class="col-sm-20 window">
    <div class="row">
        <div class="col-sm-24">
            @include('layouts.breadcrumb', ['breadcrumbs' => ['contact-us']])
            <h1>Contact Us</h1>
        </div>
    </div>
    <div class="extra-content">
        <div class="row mb-3">
            <div class="col-sm-23 mb-3">
                <strong>Your Team</strong>
            </div>
            <div class="clean-panel col-sm-5 p-1">
                Person 1
            </div>
            <div class="col-sm-1"></div>
            <div class="clean-panel col-sm-5 p-1">
                Person 2
            </div>
            <div class="col-sm-1"></div>
            <div class="clean-panel col-sm-5 p-1">
                Person 3
            </div>
            <div class="col-sm-1"></div>
            <div class="clean-panel col-sm-5 p-1">
                Person 4
            </div>
            <div class="col-sm-1"></div>
            <div class="clean-panel col-sm-5 p-1">
                Person 5
            </div>
        </div>
    </div>
    <div class="extra-content">
        <div class="row mb-3">
            <div class="clean-panel col-sm-23 p-1 pb-3">
                <h4 class="info-message-round p-3"><strong>You can call any of the team on <a class="download-button-red" href="tel:01912718266">0191 271 8266</a> or send us an email at <a class="download-button-red" href="mailto:enquiries@tcp-ltd.co.uk">enquiries@tcp-ltd.co.uk</a></strong></h4>
                <div class="col-sm-24">
                    <form id="contact-us" method="post" enctype="multipart/form-data">
                        {{csrf_field()}}
                        <input type="hidden" name="username" value="{{$user->forenames . ' ' . $user->surname}}">
                        <input type="hidden" name="branch" value="{{$branch}}">
                        <input type="hidden" name="from" value="{{$user->email}}">
                        <input type="hidden" name="recipient" value="enquiries@tcp-ltd.co.uk">
                        <label for="message"><strong>Alternatvely you can also send us a direct message below. (We'll know who it's from so don't worry about including any personal details)</strong></label>
                        <textarea class="form-control" rows="6" name="message" plaxeholder="Type your message here"></textarea>
                        <button class="success-button col-sm-24 mt-3">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
