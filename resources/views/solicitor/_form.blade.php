<div class="panel-body form-row px-4">
    <h2>Solicitor Details</h2>

    <div class="col-sm-24 form-row">
        <div class="form-group col-md-8">
            <label for="name">Solicitor Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $solicitor->name ?? null) }}" required>
            <small class="form-text text-muted">The registered name of the solicitors</small>
        </div>

        <div class="form-group col">
            <label for="url">Website</label>
            <input type="url" pattern="https?://.+" class="form-control" id="url" placeholder="http://www.your-solicitors.co.uk" name="url" value="{{ old('url', $solicitor->url ?? null) }}">
        </div>

        <div class="form-group col">
            <label for="image">Logo</label>
            <input type="file" class="form-control" id="image" name="image">
        </div>
    </div>

    <div class="col-sm-24 form-row">

        <div class="form-group col">
            <label for="referral_fee">Referral Fee</label>
            <div class="input-group">
                <span class="input-group-prepend"><span class=" input-group-text">&pound;</span></span>
                <input type="number" class="form-control" id="referral_fee" name="referral_fee" value="{{ old('referral_fee', $office->referral_fee ?? null) }}" aria-describedby="referral-help" min="0">
            </div>
            <small class="form-text text-muted" id="referral-help">How much we charge the solicitor for referring a case.</small>
        </div>

        <fieldset class="col">
            <legend>Contract Signed</legend>
            <div class="SelectionGroup">
                <input class="SelectionGroup__control" type="radio" name="contract_signed" id="contract_signed" @if(old('contract_signed')) checked @endif value="1" required>
                <label class="SelectionGroup__button btn" for="contract_signed">Yes</label>
                <input class="SelectionGroup__control" type="radio" name="contract_signed" id="contract_not_signed" @if(!old('contract_signed')) checked @endif value="0" required>
                <label class="SelectionGroup__button btn" for="contract_not_signed">No</label>
            </div>
        </fieldset>
    </div>

    <h2>Office Details</h2>

    <div class="col-sm-24 form-row">
        <div class="form-group col">
            <label for="office_name">Office Name</label>
            <input type="text" class="form-control" id="office_name" name="office_name" value="{{ old('office_name', $office->office_name ?? null) }}">
            <small class="form-text text-muted">A name to identify the office, if none is given the solicitor name will be used.</small>
        </div>


        <div class="form-group col">
            <label for="phone">Phone</label>
            <input type="tel" class="form-control" id="phone" name="phone" value="{{ old('phone', $office->phone ?? null) }}" required>
        </div>

        <div class="form-group col">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $office->email ?? null) }}" required>
        </div>
    </div>

    <div class="col-sm-24 form-row">
        <div class="form-group col">
            <label for="postcode" id="address-label">Postcode</label>
            <div class="input-group">
                <input placeholder="Postcode Search*" class="form-control" id="postcode" name="postcode" value="{{ old('postcode', $office->address->postcode ?? null) }}" required is="postcode-field">
                <div class="input-group-append">
                    <button type="button" class="btn" id="postcodeLookupBtn"><span class="fas fa-search" aria-hidden="true"></span></button>
                </div>
            </div>
        </div>

        <div class="form-group col">
            <label for="address-list">Address</label>
            <select id="address-list" class="form-control">
                <option value="" disabled selected>Choose an address&hellip;</option>
            </select>
            <a href="" id="enter-address">Or Enter Address Manually</a>
        </div>

        <div id="manual-address" class="col-sm-24 hidden">
            <div class="form-row">
                <div class="form-group col-sm-8">
                    <label for="building_name">Building Name</label>
                    <input type="text" class="form-control" placeholder="Building Name" id="building_name" name="building_name" value="{{ old('building_name', $office->address->building_name ?? null) }}">
                </div>

                <div class="form-group col-sm-8">
                    <label for="building_number">Building Number</label>
                    <input type="text" class="form-control" placeholder="Building Number" id="building_number" name="building_number" value="{{ old('building_number', $office->address->building_number ?? null) }}">
                </div>

                <div class="form-group col-sm-8">
                    <label for="address_line_1">Address Line 1</label>
                    <input type="text" class="form-control" placeholder="Address Line 1" id="address_line_1" name="address_line_1" value="{{ old('address_line_1', $office->address->address_line_1 ?? null) }}" required>
                </div>

                <div class="form-group col-sm-8">
                    <label for="address_line_2">Address Line 2</label>
                    <input type="text" class="form-control" placeholder="Address Line 2" id="address_line_2" name="address_line_2" value="{{ old('address_line_2', $office->address->address_line_2 ?? null) }}">
                </div>

                <div class="form-group col-sm-8">
                    <label for="town">Town</label>
                    <input type="text" placeholder="City/Town" class="form-control" id="town" name="town" value="{{ old('town', $office->address->town ?? null) }}" required>
                </div>

                <div class="form-group col-sm-8">
                    <label for="county">County</label>
                    <input type="text" placeholder="County" class="form-control" id="county" name="county" value="{{ old('county', $office->address->county ?? null) }}" required>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-24 form-row">
        <div class="form-group col">
            <label for="capacity">Capacity</label>
            <input type="number" class="form-control" id="capacity" name="capacity" value="{{ old('capacity', $office->capacity ?? null) }}" required min="1">
            <small class="form-text text-muted">The number of cases the office can handle.</small>
        </div>

        {{--TODO: Show / Hide TM reference field based on permissions--}}
        {{--@can('change-tm-reference', \App\SolicitorOffice::class)--}}
        <div class="form-group col">
            <label for="tm_ref">TM Reference</label>
            <input type="text" class="form-control" id="tm_ref" name="tm_ref" value="{{ old('tm_ref', $office->tm_ref ?? null) }}">
        </div>
        {{--@endcan--}}
    </div>
</div>
