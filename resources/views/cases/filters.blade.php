    <div class="col-sm-6 margin-filter">
        <label class="listing-label">View</label>
        <select class="listing-select" data-id="6" id="filterAccountManager">
            <option value="">Please select</option>
            @foreach($accountManagers as $accountManager)
                <option value="{{ $accountManager->id }}">{{ $accountManager->forenames . ' ' . $accountManager->surname }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-sm-6 margin-filter">
        <label class="listing-label">Status</label>
        <select class="listing-select" data-id="2" id="filterStatus">
            <option value="">Please select</option>
            <option value="prospect">Prospect</option>
            <option value="instructed">Instructed</option>
            <option value="exchanged">Exchanged</option>
            <option value="completed">Completed</option>
            <option value="Instructed Unpanelled">Instructed Unpanelled</option>
            <option value="aborted">Aborted</option>
            <option value="archived">Archived</option>
        </select>
    </div>

    <div class="col-sm-6 margin-filter @if(Auth::user()->user_role_id === 9) hidden @endif">
        <label class="listing-label">Agent</label>
        <select class="listing-select" data-id="7" id="filterAgent">
            <option value="">Please select</option>
            @foreach($agencies as $agency)
                <option value="{{ $agency->id }}">{{ $agency->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-sm-6 margin-filter">
        <label class="listing-label">Transaction</label>
        <select class="listing-select" data-id="3" id="filterTransaction">
            <option value="">Please select</option>
            <option value="sale">Sale</option>
            <option value="purchase">Purchase</option>
        </select>
    </div>

    <div class="col-sm-6 hidden">
        <label class="listing-label">Branch</label>
        <select class="listing-select" data-id="8" id="filterBranch">
            <option value="">Please select</option>
            @foreach($branchIds as $branchName => $branchId)
                <option data-id="{{ $branchName }}" value="{{ $branchId }}">{{ $branchId }}</option>
            @endforeach
        </select>
    </div>