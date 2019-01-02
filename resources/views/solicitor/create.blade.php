@extends('layouts.app')
@section('content')

    <div class="col-sm-20 window">

        @include('layouts.breadcrumb',
            ['breadcrumbs' =>
                [
                    'solicitors' => 'Solicitors',
                    'create' => 'Create',
                ]
            ]
        )

        <div id="page-header" class="col-sm-24">
            <h1>Add Solicitor</h1>
        </div>

        <div id="content-container" class="row">

        </div>

        <form id="add-solicitor" action="/solicitors/create" method="post" enctype="multipart/form-data">

            {{csrf_field()}}

            <div id="content-area" class="panel col-sm-23">
                @include('solicitor._form')
            </div>

            <div class="clean-panel col-sm-24" style="background:none;border:none;box-shadow:none;">
                <div class="row">
                    <div class="col-sm-12"></div>
                    <div class="col-sm-6">
                        {{--<button class="cancel-button col-sm-22" type="button">Cancel</button>--}}
                        <a href="/solicitors">
                            <button class="cancel-button col-sm-21" type="button">Cancel</button>
                        </a>
                    </div>
                    <div class="col-sm-6">
                        <button class="success-button col-sm-22">Save</button>
                    </div>
                </div>
            </div>

        </form>

    </div>

    @push('scripts')
        <script type="text/javascript">
            $(document).ready(function()
            {
                $(".cancel-button").click(function() {
                    window.history.back();
                });
            });
        </script>
    @endpush

@endsection
