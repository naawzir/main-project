@extends('layouts.app')
@section('content')
<div class="col-sm-20 window">
    <div class="row">
        <div class="col-sm-24">
            @include('layouts.breadcrumb', ['breadcrumbs' => ['training']])
            <h1>Tips & Training</h1>
        </div>
    </div>
    <div class="extra-content">
        <div class="row mb-3">
            <div class="col-sm-23 mb-3">
                <strong>This section will provide you with all the information you need to sell your in-house conveyencing service to it's fullest potential</strong>
            </div>
            <div class="panel col-sm-7 p-1">
                <div class="row">
                    <h4><strong>Your conveyancing Service USPs</strong></h4>
                </div>
                <div class="row">
                    Some text here about this download. It's really quite interesting, and keeps you motivated to read until the end.
                </div>
                <div class="row float-right">
                    <a class="download-button-red" href="https://s3.eu-west-2.amazonaws.com/tcp-ltd-assets/USPs.pdf" title="Download">
                        <i class="navigation__icon far fa-download fa-fw"></i>
                    </a>
                </div>
            </div>
            <div class="col-sm-1"></div>
            <div class="panel col-sm-7 p-1">
                <div class="row">
                    <h4><strong>Overcoming customer objections</strong></h4>
                </div>
                <div class="row">
                    Some text here about this download. It's really quite interesting, and keeps you motivated to read until the end.
                </div>
                <div class="row float-right">
                    <a class="download-button-red" href="https://s3.eu-west-2.amazonaws.com/tcp-ltd-assets/Overcoming_objections.pdf" title="Download">
                        <i class="navigation__icon far fa-download fa-fw"></i>
                    </a>
                </div>
            </div>
            <div class="col-sm-1"></div>
            <div class="panel col-sm-7 p-1">
                <div class="row">
                    <h4><strong>Sell Conveyancing to a buyer</strong></h4>
                </div>
                <div class="row">
                    Some text here about this download. It's really quite interesting, and keeps you motivated to read until the end.
                </div>
                <div class="row float-right">
                    <a class="download-button-red" href="https://s3.eu-west-2.amazonaws.com/tcp-ltd-assets/5oppsto_buyers.pdf" title="Download">
                        <i class="navigation__icon far fa-download fa-fw"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="extra-content">
        <div class="row mb-3">
            <div class="panel col-sm-23 p-1 pb-3">
                <h4><strong>Request free in-branch training</strong></h4>
                <div class="col-sm-23">
                    <form id="request-training" method="post" enctype="multipart/form-data">
                        {{csrf_field()}}
                        <input type="hidden" name="username" value="{{$user->forenames . ' ' . $user->surname}}">
                        <input type="hidden" name="branch" value="{{$branch}}">
                        <input type="hidden" name="from" value="{{$user->email}}">
                        <input type="hidden" name="recipient" value="enquiries@tcp-ltd.co.uk">
                        <div class="col-sm-16 float-left">
                            <label for="message"><strong>My Message</strong></label>
                            <textarea class="form-control" rows="6" name="message">I would like to arrange in-branch training for my team</textarea>
                        </div>
                        <div class="float-right col-sm-6">
                            <button class="success-button col-sm-22 mb-1">Save</button>
                            <button class="cancel-button col-sm-22" type="button">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="extra-content">
        <div class="row mb-3">
            <div class="panel col-sm-7 p-1">
                <div class="row">
                    <h4><strong>Sell Conveyancing to a vendor</strong></h4>
                </div>
                <div class="row">
                    Some text here about this download. It's really quite interesting, and keeps you motivated to read until the end.
                </div>
                <div class="row float-right">
                    <a class="download-button-red" href="https://s3.eu-west-2.amazonaws.com/tcp-ltd-assets/5oppsto_sellers.pdf" title="Download">
                        <i class="navigation__icon far fa-download fa-fw"></i>
                    </a>
                </div>
            </div>
            <div class="col-sm-1"></div>
            <div class="panel col-sm-7 p-1">
                <div class="row">
                    <h4><strong>The conveyancing guide</strong></h4>
                </div>
                <div class="row">
                    Some text here about this download. It's really quite interesting, and keeps you motivated to read until the end.
                </div>
                <div class="row float-right">
                    <a class="download-button-red" href="https://s3.eu-west-2.amazonaws.com/tcp-ltd-assets/conveyancingguide-GENERICTCP.pdf" title="Download">
                        <i class="navigation__icon far fa-download fa-fw"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
