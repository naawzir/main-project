@extends('layouts.app')
@section('content')
    <div class="col-sm-20 window">
        @include('layouts.breadcrumb', [
            'breadcrumbs' => [
               'my-solicitors' => 'My Solicitors',
                $office->slug => $office->solicitor->name . ' (' . $office->office_name . ')'
            ]
        ])

        <h1>{{ $solicitor->name }}, {{ $office->office_name }}</h1>

        <div class="Panel Panel--decorated">
            <div class="Panel__body">
                <p>{{ $office->address->getAddress() }}</p>
                <p><a href="{{ $solicitor->url }}">{{ $solicitor->url }}</a></p>

                <figure class="Panel Panel--highlighted">
                    <figcaption class="Panel__header Panel__title">Agent Rating</figcaption>
                    <span class="Panel__body">{{ $office->getAgentRating() ?? 'N/A' }}</span>
                </figure>

                <figure class="Panel Panel--highlighted">
                    <figcaption class="Panel__header Panel__title">Customer Rating</figcaption>
                    <span class="Panel__body">{{ $office->getCustomerRating() ?? 'N/A' }}</span>
                </figure>
            </div>
        </div>

        {{--@can('modify-panel', \App\Agency::class)--}}
        <div class="row mb-3">
            <div class="col-sm-1"></div>
            @if($partnership === 'remove')
                <button class="cancel-button" id="removeFromPanelBtn">Remove from panel</button>
            @else
                <button class="success-button" id="addToPanelBtn">Add to panel</button>
            @endif
        </div>
        {{--@endcan--}}

        <div class="mt-3">
            <h2 class="sr-only">Fees</h2>

            <div class="Panel Panel--decorated">
                <h3 class="Panel__title">Purchase Fee Scale</h3>
                <table class="table table-condensed">
                    <caption class="sr-only">Purchase Fee Scale</caption>
                    <thead>
                    <tr>
                        <th>From</th>
                        <th>To</th>
                        <th>Fee</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($purchaseFees as $fee)
                        <tr>
                            <td>&pound;{{ number_format($fee->price_from) }}</td>
                            <td>&pound;{{ number_format($fee->price_to) }}</td>
                            <td>&pound;{{ number_format($fee->legal_fee, 2) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="Panel Panel--decorated">
                <h3 class="Panel__title">Sales Fee Scale</h3>
                <table class="table table-condensed">
                    <caption class="sr-only">Sales Fee Scale</caption>
                    <thead>
                    <tr>
                        <th>From</th>
                        <th>To</th>
                        <th>Fee</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($saleFees as $fee)
                        <tr>
                            <td>&pound;{{ number_format($fee->price_from) }}</td>
                            <td>&pound;{{ number_format($fee->price_to) }}</td>
                            <td>&pound;{{ number_format($fee->legal_fee, 2) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="Panel Panel--highlighted">
                <div class="Panel__header">
                    <h3 class="Panel__title">Additional Fees</h3>
                </div>
                <div class="Panel__body">
                    <dl>
                        <dt>New Mortgage</dt>
                        <dd>&pound;{{ number_format($additionalFees->mortgage ?? 0, 2) }}</dd>
                        <dt>Mortgage Red</dt>
                        <dd>&pound;{{ number_format($additionalFees->mortgage_redemption ?? 0, 2) }}</dd>
                        <dt>Leasehold</dt>
                        <dd>&pound;{{ number_format($additionalFees->leasehold ?? 0, 2) }}</dd>
                        <dt>Archive Fee</dt>
                        <dd>&pound;{{ number_format($additionalFees->archive ?? 0, 2) }}</dd>
                    </dl>
                </div>
            </div>
        </div>

    </div>
    @push('scripts')
        <script type="text/javascript">
            $(document).ready(function() {

                $('#removeFromPanelBtn').on('click', function() {
                    $.confirm({
                        title: 'DELETE!',
                        content: 'Are you sure you want to remove this Solicitor Office from your panel?',
                        buttons: {
                            confirm: {
                                btnClass: 'btn-success',
                                action: function () {
                                    window.location.href = '/solicitors/office/{{$office->slug}}/remove-from-panel/'
                                }
                            },
                            cancel: {
                                btnClass: 'btn-red'
                            }
                        }
                    });
                });

                $('#addToPanelBtn').on('click', function() {
                    $.confirm({
                        title: 'ADD TO PANEL!',
                        content: 'Are you sure you want to add this Solicitor Office to your panel?',
                        buttons: {
                            confirm: {
                                btnClass: 'btn-success',
                                action: function () {
                                    window.location.href='/solicitors/office/{{$office->slug}}/add-to-panel/'
                                }
                            },
                            cancel: {
                                btnClass: 'btn-red'
                            }
                        }
                    });
                });

            });
        </script>
    @endpush
@endsection
