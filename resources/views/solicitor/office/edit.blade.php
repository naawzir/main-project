@extends('layouts.app')
@section('content')

    <div class="col-sm-20 window">

        @include('layouts.breadcrumb',
            ['breadcrumbs' =>
                [
                    'solicitors' => 'Solicitors',
                    'office' => 'Office',
                    $solicitorOffice->slug => $solicitor->name .' (' . $solicitorOffice->office_name . ')',
                    'edit' => 'Edit',
                ]
            ]
        )

        <div id="page-header" class="col-sm-24">
            <h1>Edit Solicitor Office: {{ $solicitorOffice->office_name }}</h1>
        </div>

        <div id="content-container" class="row">

        </div>

        <form id="add-solicitor" action="/solicitors/office/{{ $solicitorOffice->slug }}/edit" method="post" enctype="multipart/form-data">

            {{csrf_field()}}

            <div id="content-area" class="panel col-sm-23">
                @include('solicitor._form', [
                    'office' => $solicitorOffice,
                ])

                @if ($solicitorOffice->status === 'Active' || $solicitorOffice->status === 'Inactive')
                <div class="panel-body form-row px-4">
                    <div class="col-sm-24 form-row">
                        <fieldset class="col-md-12">
                            <legend>Solicitor Status</legend>
                            <div class="SelectionGroup">
                                <input class="SelectionGroup__control" type="radio" name="status" id="status_active" @if(old('status', $solicitor->status ?? null) === 'Active') checked @endif value="Active">
                                <label class="SelectionGroup__button btn" for="status_active">Active</label>
                                <input class="SelectionGroup__control" type="radio" name="status" id="status_inactive" @if(old('status', $solicitor->status ?? null) === 'Inactive') checked @endif value="Inactive">
                                <label class="SelectionGroup__button btn" for="status_inactive">Inactive</label>
                            </div>
                        </fieldset>
                    </div>
                </div>
                @endif
            </div>

            <div class="clean-panel col-sm-24" style="background:none;border:none;box-shadow:none;">
                <div class="row">
                    <div class="col-sm-12"></div>
                    <div class="col-sm-6">
                        <a href="/solicitors/office/{{ $solicitorOffice->slug }}" class="btn cancel-button col-sm-22" >Cancel</a>
                    </div>
                    <div class="col-sm-6">
                        <button type="submit" class="success-button col-sm-22">Save</button>
                    </div>
                </div>
            </div>

        </form>

    </div>

@endsection
