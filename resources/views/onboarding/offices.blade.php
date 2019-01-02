@extends('layouts.app')

@section('content')
    @php $solicitorOfficeIds = []; @endphp
    <div class="col-sm-20 window">
        @include('layouts.breadcrumb', [
            'breadcrumbs' =>
               [
                   'solicitors' => 'Solicitors',
                   'onboarding' => 'Onboarding',
                   $solicitor->slug . '/offices' => $solicitor->name,
               ]
            ]
        )
        <div id="added-content-area" class="mb-3">
            <div id="page-header" class="col-sm-23">
                <div class="row">
                    <div class="col-sm-18">
                        <h1><strong>{{ $solicitor->name }}</strong></h1>
                    </div>

                    <div class="col-sm-6">
                        <a href="/solicitors/{{ $solicitor->slug }}/office/create">
                            <button class="success-button col-sm-24 p-2" id="addoffice">Add Another Office</button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div id="content-area" class="panel col-sm-23">
            <div class="panel-body">
                <h3>Offices</h3>
                <table class="table table-bordered nohighlight" id="solicitorOfficesTable">
                    <thead>
                    <tr>
                        <th>Office</th>
                        <th>Date Created</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($solicitorOffices as $solicitorOffice)
                        <tr>
                            <td>{{ $solicitorOffice->office_name }} @if ($solicitor->default_office == $solicitorOffice->id) (Primary) @endif</td>
                            <td>{{ date('d/m/Y', strtotime($solicitorOffice->date_created)) }}</td>
                            <td><a href="/solicitors/office/{{$solicitorOffice->slug}}"><button class="col-sm-24 success-button">View</button></a></td>
                        </tr>
                        @php $solicitorOfficeIds[] = $solicitorOffice->slug; @endphp
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="extra-content clean-panel col-sm-23">
            <div class="panel-body mb-2">
                <div class="row">
                    <div class="col-sm-23 pb-3">
                        <div class="row">
                            <div class="col-sm-3"></div>
                            <div class="col-sm-3"></div>
                            <div class="col-sm-5">
                                <button class="cancel-button col-sm-23 p-2" type="button" id="panelManagerSubmissionBtn">Submit To Panel Manager</button>
                            </div>
                            <div class="col-sm-2"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
<script type="text/javascript">
    $(document).ready(() => {
        $('#panelManagerSubmissionBtn').click(() =>
        {
            let noerror = true;
            let solicitorOfficeIds = @json($solicitorOfficeIds);
            $.each(solicitorOfficeIds, function(key, value) {
                const ajaxRequest = tcp.xhr.get('/solicitors/office/' + value + '/panel-manager-submission/');

                ajaxRequest.done((data) => {
                    // do nothing
                });

                ajaxRequest.fail((error) => {
                    // TODO: Store the errors here for later use
                    noerror = false;
                });
            });

            if (noerror === true) window.location = '/solicitors/onboarding/';
            // TODO: Add the error feedback here if we are not succesful.
        });
    });
</script>
@endpush
