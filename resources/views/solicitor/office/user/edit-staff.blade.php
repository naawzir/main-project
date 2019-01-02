@extends('layouts.app')
@section('content')

    <div class="col-sm-20 window">

        @include('layouts.breadcrumb', [
            "breadcrumbs" =>
                [
                    "solicitors" => "Solicitors",
                    "solicitors/office/" . $suser->solicitorOffice->slug => "Office",
                    "solicitors/office/user/" . $suser->slug . "/edit" => $suser->forenames . ' ' . $suser->surname,
                ]
            ]
        )

    <div id="page-header" class="col-sm-23">
        <h1>Edit User: {{ $suser->forenames . ' ' . $suser->surname }}</h1>
    </div>

        <div id="content-area">

            <form id="edit-solicitor-user" action="/solicitors/office/user/{{ $suser->slug }}/edit" method="post" enctype="multipart/form-data">

                {{csrf_field()}}

                <div  class="panel col-sm-23">
                    <div class="panel-body">
                        <div class="col-sm-22 ml-5">

                            <div class="form-group">
                                <label for="title">Title</label>
                                <select id="title" name="title" class="form-control" required>
                                    <option value="">Title</option>
                                    @foreach(config('app.usertitles') as $key => $title)
                                        <option value="{{ $key }}" @if ($suser->title === $key) selected="selected" @endif> {{ old('title', $title) }} </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="forenames">Forename</label>
                                <input id="forenames" class="form-control" type="text" name="forenames" placeholder="Forename" value="{{ old('forenames', $suser->forenames) }}" required>
                            </div>

                            <div class="form-group">
                                <label for="surname">Surname</label>
                                <input id="surname" class="form-control" type="text" name="surname" placeholder="Surname" value="{{ old('surname', $suser->surname) }}" required>
                            </div>

                            <div class="form-group">
                                <label for="phone">Phone</label>
                                <input id="phone" class="form-control" type="text" name="phone" placeholder="Phone" value="{{ old('phone', $suser->phone) }}" required>
                            </div>

                            <div class="form-group">
                                <label for="phone_other">Mobile</label>
                                <input id="phone_other" class="form-control" type="text" name="phone_other" placeholder="Mobile" value="{{ old('phone_other', $suser->phone_other) }}" required>
                            </div>

                            <div class="form-group">
                                <label for="email">Email</label>
                                <input id="email" class="form-control" type="email" name="email" placeholder="Email" value="{{ old('email', $suser->email) }}" required>
                            </div>

                        </div>
                    </div>

                </div>

                <div class="clean-panel col-sm-23" style="background:none;border:none;box-shadow:none;">
                    <div class="row">
                        <div class="col-sm-11"></div>
                        <div class="col-sm-6">
                            <a href="/solicitors/office/{{ $suser->solicitorOffice->slug }}"><button class="cancel-button col-sm-24" type="button">Cancel</button></a>
                        </div>
                        <div class="col-sm-1"></div>
                        <div class="col-sm-6">
                            <button class="success-button col-sm-24">Save</button>
                        </div>
                    </div>

                </div>

            </form>
        </div>
    </div>

    @push('scripts')

    @endpush
@endsection
