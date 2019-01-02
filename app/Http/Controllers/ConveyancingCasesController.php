<?php

namespace App\Http\Controllers;

use App\User;
use App\Address;
use App\Agency;
use App\Customer;
use App\Solicitor;
use App\StaffUser;
use App\Transaction;
use App\TransactionMilestone;
use App\AgencyBranch;
use App\ConveyancingCase;
use App\ViewModels\Cases\NewCase;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\ReferenceGenerator\ReferenceGenerator;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CasesExport;
use App\Imports\CasesImport;
use Webpatser\Uuid\Uuid;

class ConveyancingCasesController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
    }

    public function index()
    {
        $agencyModel = new Agency;
        $branchModel = new AgencyBranch;
        $transactionModel = new Transaction;
        $solicitorModel = new Solicitor;
        $userStaffModel = new StaffUser;

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

        return view('cases.index', $data);
    }

    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function casesRecords(Request $request)
    {
        $date = $request->date != 'null' ? $request->date : '0';
        $myBranches = !empty($request['my-branches']) ? true : false;

        /** @var $user User */
        $user = Auth::user();
        $userRole = $user->userRole;
        $caseModel = new ConveyancingCase;
        $cases = null;

        if ($userRole->group === 'Agent') {
            /** @var $userAgent userAgent */
            $userAgent = $user->agencyUser;
            $cases = $caseModel->getCasesForAgencyUsers(
                $date,
                $user,
                $userAgent,
                $userIdAgent = false
            );
        } elseif ($userRole->group === 'Staff') {
            $cases = $caseModel->getCasesForAdminUsers($date, $myBranches);
        }

        return Datatables::of($cases)->make(true);
    }

    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCaseRecordsForBranch($branchId)
    {
        $branchModel = new AgencyBranch;
        $cases = $branchModel->getCasesForBranch($branchId);

        return Datatables::of($cases)->make(true);
    }

    public function create()
    {

        return view('cases/add', new NewCase());
    }

    public function updateCase($slug)
    {
        $case =
            ConveyancingCase::where('slug', $slug)
                ->first();

        $agency = new Agency;
        $agencies = $agency->activeAgencies();

        $solicitor = new Solicitor;
        $solicitors = $solicitor->activeSolicitors();

        /** @var $user User */
        $user = Auth::user();
        $userRole = $user->userRole()->first();

        $panelledSolicitors = null;

        $userType = null;
        // search to see if it an agency user
        if (!empty($userRole->group === 'Agent')) {
            $userType = "agency";
            /** @var $userAgent userAgent */
            $userAgent = $user->agencyUser;

            /** @var $aspanelModel AgencySolicitorPanel */
            $aspanelModel = new AgencySolicitorPanel;
            $aspanels =
                $aspanelModel
                    ->where('agency_id', $userAgent->agency_id)
                    ->get();

            $solicitorIds = [];
            foreach ($aspanels as $aspanel) {
                $solicitorIds[] = $aspanel->solicitor_id;
            }

            /** @var $solicitorModel Solicitor */
            $solicitorModel = new Solicitor;
            $panelledSolicitors =
                $solicitorModel
                    ->whereIn('id', $solicitorIds)
                    ->where('active', 1)
                    ->orderBy('name', 'asc')
                    ->get();
        } elseif (!empty($userRole->group === 'Staff')) {
            $userType = "admin";
        }

        $userModel = new User;
        $accountManagers = $userModel->getUsersByRole([5, 6]);

        $data = [

            'reference' => $case->reference,
            'agencies' => $agencies,
            'solicitors' => $solicitors,
            'panelledSolicitors' => $panelledSolicitors,
            'accountManagers' => $accountManagers,
            'userType' => $userType,
            'case' => $case,

        ];

        return view('cases.add', $data);
    }

    /**
     * Get a specified Case Overview Details via CaseID
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function caseOverviewDetails(Request $request)
    {
        $case = loadRecord(new ConveyancingCase, $request->caseId);
        $transaction = $case->transaction();
        $transCustomer =
            $transaction
                ->transactionCustomers()
                ->where('customer_number', 1)
                ->first();

        if (!$transCustomer) {
            return response()->json([
                'success' => false,
                'error' => 'Could not find associated Customer!'
            ]);
        }

        $customer = Customer::find($transCustomer->customer_id);
        $corr_address = Address::find($customer->address_id);

        $last_milestone = TransactionMilestone::where('transaction_id', $case->id)
            ->orderBy('date_updated', 'desc')
            ->first();

        $milestone = null;

        $milestoneNumber = null;
        if ($last_milestone) {
            //$milestone = Milestones::where('id', $last_milestone->milestone_id)->first();
            $milestone = Milestones::find($last_milestone->milestone_id);
            $milestone = str_replace('_', ' ', $milestone);
            $milestone = ucfirst($milestone);
            $milestone = json_decode($milestone);
            $milestoneNumber = $milestone->number;
        }

        return response()->json([
            'success' => true,
            'data' => [
                'customer' => $customer,
                'corr_address' => $corr_address,
                'last_milestone' => $milestoneNumber
            ]
        ]);
    }

    public function generateCasesReport(Request $request)
    {
        $viewId = $request->get('view_id', null);
        $agencyId = $request->get('agent_id', null);
        $status = $request->get('status', null);
        $transaction = $request->get('transaction', null);
        return Excel::download(new CasesExport(
            $agencyId,
            $viewId,
            $status,
            $transaction
        ), 'cases.xlsx');
    }
}
