@if(!isset($agent))
    <h2>Agent Details</h2>

    <div class="Panel Panel--decorated">
        <div class="Panel__body">
            <div class="form-row">
                <div class="col-lg-6 form-group">
                    <label for="agent">Agent</label>
                    <select name="agent" id="agent" class="form-control" required>
                        <option value="" selected disabled>Select an agent&hellip;</option>
                        @foreach($agents as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-6 form-group">
                    <label for="branch">Branch</label>
                    <select name="branch" id="branch" class="form-control" required>
                        <option value="" selected disabled>Select a branch&hellip;</option>
                        @foreach($branches as $id => $agency)
                            <optgroup label="{{ $agency->name }}">
                            @foreach($agency->branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @endforeach
                        </optgroup>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-6 form-group">
                    <label for="staff">Agency Staff</label>
                    <select name="staff" id="staff" class="form-control" required>
                        <option value="" selected disabled>Select someone&hellip;</option>
                        @foreach($agencyStaff as $agency => $staff)
                            <optgroup label="{{ $agency }}">
                            @foreach($staff as $id => $person)
                                <option value="{{ $id }}">{{ $person->forenames }} {{ $person->surname }}</option>
                            @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-6 form-group">
                    <label for="sales_progressor">Sales Progressor</label>
                    <select name="sales_progressor" id="sales_progressor" class="form-control">
                        <option value="" selected>Select someone&hellip;</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
@endif

<div class="panel-body">
    <div class= "panel col-sm-24">
        <h3><strong>Client Details</strong></h3>
        <div class="row">
            <div class="form group" style="padding-left:10px;">
                <label for="name"><strong>Title</strong></label>
                <input type="select" class="form-control" placeholder="Forename" value="">
            </div>
            <div class="form group" style="padding-left:10px;">
                <label for="name"><strong>Forenames</strong></label>
                <input type="text" class="form-control" placeholder="Forename" value="">
            </div>
            <div class="form group" style="padding-left:10px;">
                <label for="name"><strong>Surname</strong></label>
                <input type="text" class="form-control" placeholder="Surname" value="">
            </div>
            <div class="form group" style="padding-left:10px;">
                <label for="name"><strong>Email</strong></label>
                <input type="text" class="form-control" placeholder="Email" value="">
            </div>
            <div class="form group" style="padding-left:10px;">
                <label for="name"><strong>Phone</strong></label>
                <input type="text" class="form-control" placeholder="Phone" value="">
            </div>
            <div class="form group" style="padding-left:10px; padding-bottom:15px;">
                <label for="name"><strong>Date Of Birth</strong></label>
                <input type="date" class="form-control" placeholder="Email" value="">
            </div>
        </div>
        <p><a href="">+ Add Another Client</a></p>
    </div>
</div>
<div class="panel-body">
    <div class= "panel col-sm-24">
        <h3><strong>Client Address Details</strong></h3>
        <div class="row">
            <div class="form group" style="padding-left:10px;">
                <label for="name"><strong>Client</strong></label>
                <input type="text" class="form-control" placeholder="Client" value="">
            </div>
            <div class="form-group" style="padding-left:15px;">
                <label for="postcode" id="address-label"><strong>Address</strong></label>
                <div class="row">
                    <input type="text" pattern="[a-zA-Z0-9 ]+" placeholder="Postcode Search*" class="col-sm-20 form-control" id="postcode" name="postcode" value="{{ old('postcode') }}" required>
                    <button type="button" class="col-sm-3" id="postcodeLookupBtn"><i class="fas fa-search"></i></button>
                </div>
            </div>
            <div class="form-group">
                <select id="address-list" class="form-control hidden"></select>
                <a href="" id="enter-address">Or Enter Address Manually</a>
            </div>
            <div id="manual-address" class="hidden">
                <div class="form-group">
                    <label for="building_name"><strong>Building Name</strong></label>
                    <input type="text" class="form-control" placeholder="Building Name" id="building_name" name="building_name" value="{{ old('building_name') }}">
                </div>
                <div class="form-group">
                    <label for="building_number"><strong>Building Number</strong></label>
                    <input type="text" class="form-control" placeholder="Building Number" id="building_number" name="building_number" value="{{ old('building_number') }}">
                </div>
                <div class="form-group">
                    <label for="address_line_1"><strong>Address Line 1</strong></label>
                    <input type="text" class="form-control" placeholder="Address Line 1" id="address_line_1" name="address_line_1" value="{{ old('address_line_1') }}" required>
                </div>
                <div class="form-group">
                    <label for="address_line_2"><strong>Address Line 2</strong></label>
                    <input type="text" class="form-control" placeholder="Address Line 2" id="address_line_2" name="address_line_2" value="{{ old('address_line_2') }}">
                </div>
                <div class="form-group">
                    <label for="town"><strong>Town</strong></label>
                    <input type="text" placeholder="City/Town" class="form-control" id="town" name="town" value="{{ old('town') }}" required>
                </div>
                <div class="form-group">
                    <label for="county"><strong>County</strong></label>
                    <input type="text" placeholder="County" class="form-control" id="county" name="county" value="{{ old('county') }}" required>
                </div>
            </div>
        </div>

        <p><a href="">+ Add Another Clients Address</a></p>
    </div>
</div>
<div class="panel-body">
    <div class= "panel col-sm-24">
        <h3><strong>Transaction Details</strong></h3>
        <div class="row">
            <div class="form group" style="padding-left:10px;">
                <label for="name"><strong>Case Type</strong></label>
                <input type="text" class="form-control" placeholder="CaseType" value="">
            </div>
            <div class="form group" style="padding-left:10px;">
                <label for="name"><strong>Lead Source</strong></label>
                <input type="text" class="form-control" placeholder="Source" value="">
            </div>
            <div class="form group" style="padding-left:10px;">
                <label for="name"><strong>Price</strong></label>
                <input type="text"  class="form-control" placeholder="Price" value="">
            </div>
            <div class="form group" style="padding-left:10px;">
                <label for="name"><strong>Tenature</strong></label>
                <input type="text"  class="form-control" placeholder="Tenature" value="">
            </div>
            <div class="form group" style="padding-left:10px;">
                <label for="name"><strong>Mortgage</strong></label>
                <input type="text"  class="form-control" placeholder="Mortgage" value="">
            </div>
            <div class="form group" style="padding-left:10px;">
                <label for="name"><strong>Searches Required</strong></label>
                <input type="text"  class="form-control" placeholder="" value="">
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="form group">
                <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                <label class="form-check-label" for="defaultCheck1">Same As Clients Address</label>
            </div>
        </div>
        <div class="form-group">
            <label for="postcode" id="address-label"><strong>Address</strong></label>
            <div class="row">
                <input type="text" pattern="[a-zA-Z0-9 ]+" placeholder="Postcode Search*" class="col-sm-20 form-control" id="postcode" name="postcode" value="{{ old('postcode') }}" required>
                <button type="button" class="col-sm-3" id="postcodeLookupBtn"><i class="fas fa-search"></i></button>
            </div>
        </div>
        <div class="form-group">
            <select id="address-list" class="form-control hidden"></select>
            <a href="" id="enter-address">Or Enter Address Manually</a>
        </div>
        <div id="manual-address" class="hidden">
            <div class="form-group">
                <label for="building_name"><strong>Building Name</strong></label>
                <input type="text" class="form-control" placeholder="Building Name" id="building_name" name="building_name" value="{{ old('building_name') }}">
            </div>
            <div class="form-group">
                <label for="building_number"><strong>Building Number</strong></label>
                <input type="text" class="form-control" placeholder="Building Number" id="building_number" name="building_number" value="{{ old('building_number') }}">
            </div>
            <div class="form-group">
                <label for="address_line_1"><strong>Address Line 1</strong></label>
                <input type="text" class="form-control" placeholder="Address Line 1" id="address_line_1" name="address_line_1" value="{{ old('address_line_1') }}" required>
            </div>
            <div class="form-group">
                <label for="address_line_2"><strong>Address Line 2</strong></label>
                <input type="text" class="form-control" placeholder="Address Line 2" id="address_line_2" name="address_line_2" value="{{ old('address_line_2') }}">
            </div>
            <div class="form-group">
                <label for="town"><strong>Town</strong></label>
                <input type="text" placeholder="City/Town" class="form-control" id="town" name="town" value="{{ old('town') }}" required>
            </div>
            <div class="form-group">
                <label for="county"><strong>County</strong></label>
                <input type="text" placeholder="County" class="form-control" id="county" name="county" value="{{ old('county') }}" required>
            </div>
        </div>
    </div>
</div>
<div class="panel-body" >
    <div class= "panel col-sm-24">
        <h3><strong>Addition Questions</strong></h3>
    </div>
</div>
<div class="Panel Panel--decorated">
    <h3 class="Panel__title">Addition Questions</h3>
</div>
<div class="Panel Panel--decorated">
    <h3 class="Panel__title">Documents</h3>
</div>
<div class="Panel Panel--decorated">
    <h3 class="Panel__title">Solicitor</h3>
</div>
