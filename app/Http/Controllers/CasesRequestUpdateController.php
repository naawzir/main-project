<?php

namespace App\Http\Controllers;

use App\Address;
use App\Agency;
use App\AgencyBranch;
use App\AgentUpdateRequest;
use App\Mail\RequestUpdate;
use App\Solicitor;
use App\SolicitorOffice;
use App\StaffUser;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Transaction;
use App\ConveyancingCase;
use Yajra\Datatables\Datatables;

class CasesRequestUpdateController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        /** @var $user User */
        $user = Auth::user();

        $case = loadRecord(new ConveyancingCase, $request->case);
        $address = $case->transaction()->address;
        $solicitor = Solicitor::find($case->solicitor_id);
        $solicitorOffice = SolicitorOffice::find($case->solicitor_office_id);
        $pmemail = User::select('email')->where('user_role_id', '4')->first();

        $data = [
            'user' => $user,
            'case' => $case,
            'address' => $address,
            'solicitor' => $solicitor,
            'solicitorOffice' => $solicitorOffice,
            'pmemail' => $pmemail,
        ];

        return view('cases.request-update', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
/*        $case =
            Cases::where('slug', $request->case)
                ->first();*/
        $case = loadRecord(new ConveyancingCase, $request->case);

        /** @var $user User */
        $user = Auth::user();

        //Mail::to($request->recipient)->send(new RequestUpdate($request));
        Mail::to('jay.dunlavy@intelligentservicesgroup.com')->send(new RequestUpdate($request));

        $agentUpdateRequests = new AgentUpdateRequest;
        $agentUpdateRequests->case_id = $case->id;
        $agentUpdateRequests->recipient_email = $request->recipient;
        $agentUpdateRequests->message = $request->message;
        $agentUpdateRequests->save();

        return redirect('/cases/')->with('message', 'Request Update Email Sent');
    }

    public function show()
    {
        /** @var $user User */
        $user = Auth::user();

        $branchModel = new AgencyBranch;
        $transactionModel = new Transaction;
        $solicitorModel = new Solicitor;
        $agencyModel = new Agency;
        $userStaffModel = new StaffUser;
        $conveyanceCaseModel = new ConveyancingCase;

        $branches = $branchModel->branchPerformanceBranches();
        $branchIds = $branchModel->branchesFilterOptions($branches);

        $data = [
            'branches' => $branchIds,
            'branchIds' => $branchIds,
            'types' => $transactionModel->getTypes(),
            'solicitors' => $solicitorModel->activeSolicitors(),
            'agencies' => $agencyModel->getDistinctAgencies(),
            'accountManagers' => $userStaffModel->getAccMans(),
        ];

        return view('cases.update-requests.index', $data);
    }

    public function getUpdateRequests(Request $request)
    {
        $agentURModel = new AgentUpdateRequest;
        $requests = $agentURModel->agentUpdateRequests();

        $cases = [];
        $i = 0;
        foreach ($requests as $request) {
            $caseModel = $request->conveyancingCase;
            $addressModel = $caseModel->address->getAddress();
            $solicitorModel = $caseModel->solicitor;
            $userModel = $caseModel->userStaff;
            $agencyModel = $caseModel->agency;

            $cases[$i]['account_manager_user_id'] = $userModel->id;
            $cases[$i]['account_manager_name'] = $userModel->forenames . ' ' . $userModel->surname;
            $cases[$i]['agent_id'] = $agencyModel->id;
            $cases[$i]['agent_name'] = $agencyModel->name;
            $cases[$i]['case_id'] = $caseModel->id;
            $cases[$i]['slug'] = $caseModel->slug;
            $cases[$i]['reference'] = $caseModel->reference;
            $cases[$i]['transaction'] = $caseModel->type;
            $cases[$i]['status'] = $caseModel->status;
            $cases[$i]['TransactionAddress'] = $addressModel;
            $cases[$i]['Solicitor'] = $solicitorModel->name;
            $cases[$i]['date_created'] = date('d/m/Y', strtotime($caseModel->date_created));
            $cases[$i]['update_request_date'] = date('d/m/Y', strtotime($request->date_created));

            $i++;
        }

        return Datatables::of($cases)->make(true);
    }
}
