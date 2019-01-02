@extends('layouts.app')
@section('content')
    <style>
        .selected {
            background:#E3BB4B;
        }
    </style>

<div class="col-sm-20 window">

    @include('layouts.breadcrumb',
           ['breadcrumbs' =>
               [
                   'feedback' => 'Feedback',
               ]
           ]
       )

    <div id="page-header" class="col-sm-24"></div>

    <div id="content-container" class="row" style="margin-bottom: 2rem;">
        <div id="added-content-area" class="Panel col-sm-23">
            <div id="monthlyFeedbackHeaderSection">
                <img id="monthlyFeedbackHeaderImage" src="/images/monthly_feedback_header.jpg" class=col-sm-24>
                <p id="header">Monthly Feedback</p>
                <p id="mainText">Please spare us 30 seconds</p>
                <p id="lastText">Your ongoing feedback is important to us to ensure our service is first class.</p>
            </div>
        </div>
    </div>

    <form action="/feedback/store" method="post" id="feedbackForm">

        {{csrf_field()}}

        <div id="content-area" class="col-sm-23">
            <div class="Panel Panel--flat">
                <h2 class="Panel__title FeedbackForm__header">Please rate the overall service from the TCP sales team in the last 30 days</h2>
                <div class="Panel__body row justify-content-center">
                    @php $i = 1; @endphp
                    @include('partials._feedback', [
                        'name' => 'score',
                    ])
                </div>
            </div>

            <div class="Panel Panel--flat">
                <h2 class="Panel__title FeedbackForm__header">Please rate your panel of Solicitors</h2>

                @forelse($cases as $case)
                    @php $i++ @endphp
                    <div class="row justify-content-center">
                        <h3>{{ $case->solicitorOffice->solicitor->name . ': ' . $case->solicitorOffice->office_name }}</h3>

                        @include('partials._feedback', [
                            'name' => "score[{$case->solicitorOffice->slug}]",
                        ])
                    </div>
                @empty
                    <p class="text-center">You don't have any solicitors to rate this month.</p>
                @endforelse
            </div>

            <div class="Panel Panel--flat">
                <h2 class="Panel__title"><label for="comments" class="FeedbackForm__header">Any additional comments or complaints?</label></h2>
                <div class="Panel__body">
                    <textarea id="comments" name="comments" class="form-control" rows="5">{{ old('comments') }}</textarea>
                </div>

                <div class="Panel__body text-center">
                    <button class="success-button" id="submitBtn" type="submit">Submit feedback</button><br>
                    <a href="{{ route('home') }}">Skip Survey</a>
                </div>
            </div>

            <meta name="rating-sections-count" content="{{ $i }}">
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {

            $(".solicitor_office_rating_box .btn-rating").click(function() {
                $(this).parent().find(".box").removeClass("selected");
                $(this).addClass("selected");
            });

            $("#submitBtn").click(function(e) {
                e.preventDefault();
                const warningBox = $('.warning-box');
                if (warningBox.length) {
                    warningBox.remove();
                }

                const ratingBoxesCount = $("meta[name='rating-sections-count']").attr('content');
                const ratingBoxesSelectedCount = $('input:radio:checked').length;

                if (ratingBoxesCount == ratingBoxesSelectedCount) {
                    $("#feedbackForm").submit();
                } else {
                   $('#content-container').prepend(`<div data-notification="warning" data-hover="Dismiss Notification?" class="col-sm-23 warning-box"><p>Please rate all options below.</p></div>`); 
                }
                return; 
            });
        });
    </script>
@endpush
