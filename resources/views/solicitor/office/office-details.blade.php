<div class="panel col-sm-23">
    <h3>Office Details</h3>
    @if($solicitor->defaultOffice->id === $solicitorOffice->id)
        <h6>(Primary office)</h6>
    @endif
    <div class="formdiv col-sm-16 ml-5">
        <p>Address: {{ $solicitorOffice->address->getAddress() }}</p>
        <p>Tel: {{ $solicitorOffice->phone }}</p>
        <p>Email: {{ $solicitorOffice->email }}</p>
        <p>Capacity: {{ $solicitorOffice->capacity }}</p>
        <p id="tm_ref">TM Ref: {{ $solicitorOffice->tm_ref }}</p>
        <p>Contract Signed:{{ !empty($solicitor->contract_signed) ? 'Yes' : 'No' }}</p>
    </div>
    <div class="row col-sm-5 ml-5">
    @if (!empty($solicitorOffice->image_title))
        <div class="col-sm-5 float-left">
            <img class="logoimg" src="https://s3.eu-west-2.amazonaws.com/tcp-solicitor-marketplace/{{str_replace('//', '/', $solicitorOffice->image_title)}}" />
        </div>
    @endif
        <div class="col-sm-10 "></div>
        <div class="col-sm-15 float-right p-2">
            @yield('editarea')
        </div>
    </div>
</div>
