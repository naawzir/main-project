
    <div id="branchContactNotesSection" class="panel col-sm-23">
        <div class="row">
            <div class="col-sm-21">
                <h5><strong>Branch Contact</strong></h5>
            </div>
            <div class="col-sm-3">
                <button class="success-button col-sm-22" id="addContactNoteBtn">Add Contact Note</button>
            </div>
        </div>

        <div class="panel-body">
            <table class="table table-bordered" id="branchContactTable">
                <thead>
                <tr>
                    <th style="width:10%;">Date</th>
                    <th style="width:20%;">Staff</th>
                    <th style="width:70%;">Last Contact</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>


    <div id="addBranchContactNoteSection" class="panel col-sm-23 hidden">
        <div class="row">
            <div class="col-sm-15">
                <h5><strong>Branch Contact</strong></h5>
            </div>

            <div class="col-sm-3">
                <button class="col-sm-22 cancel-button" id="addBranchContactNoteCancel">Cancel</button>
            </div>

            <div class="col-sm-3">
                <button class="col-sm-22 success-button create-branch-contact-note" data-send="false" id="addBranchContactNoteSave">Save</button>
            </div>

            <div class="col-sm-3">
                <button class="col-sm-22 success-button create-branch-contact-note" data-send="true" id="addBranchContactNoteSaveAndNotify">Save & Notify</button>
            </div>
        </div>
        <br>
        <textarea id="branchContactNote" rows="6" class="col-sm-24" placeholder="Please start typing"></textarea>
    </div>