<?php
namespace App\Http\Controllers;

use App\Address;
use App\Disbursement;
use App\Rules\OfficeName;
use App\Solicitor;
use App\SolicitorOffice;
use App\SolicitorUser;
use App\Task;
use App\User;
use App\AgencySolicitorPanel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Webpatser\Uuid\Uuid;

class SolicitorOfficesController extends Controller
{

    public function create($solicitorId)
    {
        $solicitor = loadRecord(new Solicitor, $solicitorId);

        $data = [
            'solicitor' => $solicitor,
        ];

        return view('solicitor.office.create', $data);
    }

    public function store(Request $request)
    {
        /** @var $user User */
        $user = Auth::user();
        $addressModel = new Address;
        $address = $addressModel->saveAddress($request, 'Solicitor Office');
        $solicitor = loadRecord(new Solicitor, $request->solicitor);

        if ($address) {
            $request->validate([
                'office_name' => ['required', new OfficeName($solicitor)],
                'phone' => 'required',
                'capacity' => 'required',
                'email' => 'required|unique:solicitor_offices',
                'referral_fee' => 'required|numeric',
                'image' => 'image',
            ]);

            $solicitorOffice = new SolicitorOffice;
            $solicitorOffice->solicitor_id = $solicitor->id;
            $solicitorOffice->address_id = $address->id;
            $solicitorOffice->office_name = ucfirst(strtolower($request->office_name));
            $solicitorOffice->phone = $request->phone;
            $solicitorOffice->email = $request->email;
            $solicitorOffice->tm_ref = $request->tm_ref;
            $solicitorOffice->capacity = $request->capacity;
            $solicitorOffice->status = 'Pending';
            $solicitorOffice->referral_fee = $request->referral_fee;
            $solicitorOffice->save();
        }

        if (!empty($request->image)) {
            $storagePath = Storage::disk('s3')->put(
                $solicitor->slug . '/' . $solicitorOffice->slug . '/',
                $request->image,
                'public'
            );

            $solicitorOffice->image_title = $storagePath;
            $solicitorOffice->save();
        }

        if (empty($solicitor->default_office)) { // this is an office being created for a new solicitor
            $solicitor->default_office = $solicitorOffice->id;
            $solicitor->save();
        }

        return redirect('/solicitors/office/' . $solicitorOffice->slug. '/user/create')
            ->with('message', 'Solicitor Office Created');
    }

    private function solicitorDetailedView($solOfficeId)
    {
        $user = Auth::user();
        $agencyId = $user->agencyUser->agency_id;
        $agencyPanelId = new AgencySolicitorPanel;
        $panel =
            $agencyPanelId->agencySolicitorOfficePartnership($agencyId, $solOfficeId);

        /*
         * this checks if the Agency and Office have a partnership.
         * depending on the result of the above determines which button (remove or add) will be displayed in the view
         */
        return !empty($panel) ? 'remove' : 'add';
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $solicitorOffice = loadRecord(new SolicitorOffice, $request->office);
        /** @var $user User */
        $user = Auth::user();
        $user_role = $user->userRole()->first();
        $solicitor = $solicitorOffice->solicitor;
        $solicitorAddress = $solicitorOffice->address;
        $additionalFees = $solicitorOffice->additionalFees;
        $referralFee = $solicitorOffice->referral_fee;
        $mydisbursements = $solicitorOffice->disbursements->pluck('disbursement_id')->toArray();
        //dd($mydisbursements);
        $disbursements = Disbursement::getDisbursementsFromArray(array_values($mydisbursements));
        //dd($disbursements);
        $data = [
            'solicitor' => $solicitor,
            'solicitorOffice' => $solicitorOffice,
            'solicitorAddress' => $solicitorAddress,
            'additionalFee' => $additionalFees,
            'referral_fee' => $referralFee,
            'user' => $user,
            'disbursements' => $disbursements,
        ];
        $view = 'solicitor.office.show';
        /* Disabled the below for now as it's not currently being used. */
//        if ($user_role->dashboard_title === 'business-owner') {
//            $feeStructurePurchase = $solicitorOffice->getFees('purchase');
//            $feeStructureSale = $solicitorOffice->getFees('sale');
//
//            $partnership = $this->solicitorDetailedView($solicitorOffice->id);
//            $data['purchaseFees'] = $feeStructurePurchase;
//            $data['saleFees'] = $feeStructureSale;
//            $data['partnership'] = $partnership;
//            $view = 'solicitor.office.solicitor-detailed';
//        }

        return view($view, $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($solicitorOfficeId)
    {
        $solicitorOffice = loadRecord(new SolicitorOffice, $solicitorOfficeId);

        // get models and send through to view
        $solicitor = $solicitorOffice->solicitor;
        $solicitorAddress = $solicitorOffice->address;

        $data = [
            'solicitor' => $solicitor,
            'solicitorOffice' => $solicitorOffice,
            'solicitorAddress' => $solicitorAddress,
        ];

        return view('solicitor.office.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $solicitorOffice = loadRecord(new SolicitorOffice, $request->solicitorOffice);
        $solicitor = $solicitorOffice->solicitor;

        /** @var $user User */
        $user = Auth::user();
        $address = $solicitorOffice->address;
        $address->saveAddress($request, $targetType = false);

        $request->validate([
            'name' => 'required|unique:solicitors,name,' . $solicitor->id,
            'office_name' => ['required', new OfficeName($solicitor, $solicitorOffice)],
            'phone' => 'required',
            'capacity' => 'required',
            'email' => 'required|unique:solicitor_offices,email,' . $solicitorOffice->id,
            'referral_fee' => 'required|numeric'
        ]);

        $solicitorOffice->office_name = $request->office_name;
        $solicitorOffice->email = $request->email;
        $solicitorOffice->phone = $request->phone;
        $solicitorOffice->tm_ref = $request->tm_ref;
        $solicitorOffice->capacity = $request->capacity;
        $solicitorOffice->status = !empty($request->status) ? $request->status : $solicitorOffice->status;
        $solicitorOffice->referral_fee = $request->referral_fee;
        $solicitorOffice->save();

        if ($solicitorOffice->id == $solicitor->default_office) {
            $solicitor->status = $request->status;
            $solicitor->url = $request->url;
            $solicitor->save();
        }

        if (!empty($request->image)) {
            $storagePath = Storage::disk('s3')->put(
                $solicitor->slug . '/' . $solicitorOffice->slug . '/',
                $request->image,
                'public'
            );

            $solicitorOffice->image_title = $storagePath;
            $solicitorOffice->save();
        }

        return redirect('/solicitors/office/' . $solicitorOffice->slug)
            ->with('message', 'Solicitor Office Updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($solicitorOffice)
    {
        /** @var $user User */
        $user = Auth::user();
        SolicitorOffice::destroy($solicitorOffice);
        return redirect('/solicitors')->with('message', 'Solicitor Office Deleted');
    }

    public function getUsersForOffice(Request $request)
    {
        /** @var $user User */
        $user = Auth::user();
        $solicitorOfficeId = $request->query('solicitorOfficeId', null);
        $userSolicitorOffice = new SolicitorUser;
        $solicitorUsers = $userSolicitorOffice->users($solicitorOfficeId);
        $html = '';

        if (!$solicitorUsers->isEmpty()) {
            $html .= '<option value="">Please select' . ' (' . count($solicitorUsers) . ')' . '</option>';

            foreach ($solicitorUsers as $solicitorUser) {
                $html .= sprintf(
                    '<option class="ajax-options" value="%s">%s</option>',
                    $solicitorUser->id,
                    $solicitorUser->fullname()
                );
            }
        } else {
            $html .= '<option value="">No users assigned to this office</option>';
        }

        return response()->json([
            'success' => true,
            'data' => [
                'options' => $html
            ]
        ]);
    }

    public function panelManagerSubmission(Request $request)
    {
        /** @var $user User */
        $user = Auth::user();
        $panelUser = User::where('user_role_id', '=', '4')->first();
        $solicitorOffice = loadRecord(new SolicitorOffice, $request->solicitorOffice);
        $taskModel = new Task;
        $solicitorOffice->status = 'Onboarding';
        $solicitorOffice->save();
        $message =
            $solicitorOffice->solicitor->name .
            ' has been set up with a new office: ' .
            $solicitorOffice->office_name .
            ' . Please check the details and then submit to TM';

        if (Task::where([
                ['target_id', '=', $solicitorOffice->id],
                ['type', '=', 'complete-onboarding']
            ])->count() > 0
        ) {
            $taskModel->clearTask($solicitorOffice->id, 'complete-onboarding');
        }

        if ($task = $taskModel->createTask(
            Uuid::generate(4)->string,
            'New Solicitor Office',
            $solicitorOffice->id,
            'solicitor-office',
            'new-solicitor-office',
            $message,
            time(),
            $panelUser->id
        )) {
            return response()->json([
                'success' => true,
                'data' => [
                    'message' => $solicitorOffice->solicitor->name .
                        ' ' . $solicitorOffice->office_name .
                        ' has been sent to the panel manager'
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'data' => [
                'message' => $solicitorOffice->solicitor->name .
                    ' ' . $solicitorOffice->office_name .
                    ' has not been sent to the panel manager'
            ]
        ]);
    }

    public function submitToTM(Request $request)
    {
        /** @var $user User */
        $user = Auth::user();
        $solicitorOffice = loadRecord(new SolicitorOffice, $request->solicitorOffice);
        $solicitorOffice->status = 'SentToTM';
        $solicitorOffice->tm_ref = 'TM' . substr(time(), -5);
        $solicitorOffice->save();
        $message =
            $solicitorOffice->solicitor->name . ' ' . $solicitorOffice->office_name .
            ' has been sent to TM. Please check the details and then submit to Marketplace';

        $taskModel = new Task;
        $task = $taskModel->createTask(
            Uuid::generate(4)->string,
            'Solicitor Office Sent To TM',
            $solicitorOffice->id,
            'solicitor-office',
            'alert',
            $message,
            time(),
            $user->id
        );

        if ($task) {
            return response()->json([
                'success' => true,
                'data' => [
                    'message' => $solicitorOffice->solicitor->name .
                        ' ' . $solicitorOffice->office_name .
                        ' has been sent to TM',
                    'tm_ref' => $solicitorOffice->tm_ref,
                ]
            ]);
        }
        return false;
    }

    public function submitToMarketplace(Request $request)
    {
        /** @var $user User */
        $user = Auth::user();
        $solicitorOffice = loadRecord(new SolicitorOffice, $request->solicitorOffice);
        $solicitorOffice->status = 'Active';
        $solicitorOffice->save();
        $solicitor = $solicitorOffice->solicitor;

        if ($solicitorOffice->id == $solicitor->default_office) {
            $solicitor->status = 'Active';
            $solicitor->save();
        }

        $message =
            $solicitorOffice->solicitor->name . ' ' . $solicitorOffice->office_name .
            ' has been set to Active. Please check the details.';

        $taskModel = new Task;
        $task = $taskModel->createTask(
            Uuid::generate(4)->string,
            'Solicitor Office Activated',
            $solicitorOffice->id,
            'solicitor-office',
            'alert',
            $message,
            time(),
            $user->id
        );

        if ($task) {
            return response()->json([
                'success' => true,
                'data' => [
                    'message' => $solicitorOffice->solicitor->name .
                        ' ' . $solicitorOffice->office_name .
                        '  has been set to Active. Please check the details.',
                ]
            ]);
        }
        return false;
    }
}
