@extends('layouts.app')
@section('content')
<div class="col-sm-20 window">
    <div class="row">
        <div class="col-sm-24">
            @include('layouts.breadcrumb', array("breadcrumbs" => ["task" => "Tasks", $task->id=>$task->title]))
            <h1>{{$task->title}}</h1>
        </div>
    </div>
    <div id="content-area">
        <div class="row">
            <section class="panel col-sm-18">
                <h3>{{$task->title}} Task Details</h3>
                <form id="task-edit" name="task-edit" action="/tasks/update/{{$task->id}}" enctype="multipart/form-data" method="post">
                    <input type="hidden" name="nmethod" id="nmethod" value="complete" />
                    <input type="hidden" name="id" id="id" value="{{$task->id}}" />
                    {{csrf_field()}}
                    <div class="row field-set">
                        <label class="col-sm-3">Task: </label>&nbsp;<span id="notes-text"></span>
                    </div>
                    <div class="row field-set">
                        <label for="date_due" class="col-sm-3">Date Due: </label>
                        <input type="text" name="date_due" value="{{date('d/m/Y',$task->date_due)}}" class="datepicker" id="date_due" placeholder="Please select">
                        <label for="assigned_to" class="col-sm-3">Assigned To: </label>
                        <select id="assigned_to" name="assigned_to">
                            @foreach($usersinrole as $userinrole)
                            <option {{ $task->assigned_to === $userinrole->id ? 'selected' : ''  }} value="{{$userinrole->id}}">
                            {{ $userinrole->forenames . ' ' . $userinrole->surname }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row field-set">
                        <label class="col-sm-3" for="task_notes">Task Notes:</label>
                        <textarea class="col-sm-18" name="task_notes" id="task_notes" rows="6">{{$task->task_notes}}</textarea>
                    </div>
                    <div class="row field-set">
                        <div class="col-sm-3"></div>
                        <div class="col-sm-20">
                            <button class="col-sm-5 cancel-button" type="button" id="cancel">Cancel</button>
                            <button class="col-sm-5 success-button" type="button" id="view-more">View {{formatDisplay($task->target_type)}}</button>
                            <button class="col-sm-5 cancel-button" type="submit" id="save">Reschedule Task</button>
                            <button class="col-sm-5 success-button" type="submit" id="complete">Complete Task</button>
                        </div>
                    </div>
                </form>
            </section>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script type="text/javascript">
    $(document).ready(function()
    {
        document.getElementById('notes-text').innerHTML = strip('{{$task->notes}}');

        $(".datepicker").datepicker({
            changeMonth: true,
            changeYear: true,
            firstDay: 1,
            minDate: 0,
            dateFormat: 'dd/mm/yy'
        });

        $('#view-more').on('click', () => {
            window.location = '@php echo $url_ext @endphp'; // using php echo rather than braces as braces add html encoding
        });

        $('#save').on('click', () => {
            $('#nmethod').val('save');
        });

        $('#complete').on('click', () => {
            $('#nmethod').val('complete');
        });

        $('#cancel').on('click', () => {
            window.location = '/';
        });
    });
</script>
@endpush
