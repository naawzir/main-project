<?php
namespace App\Http\Controllers;

use App\Address;
use App\ConveyancingCase;
use App\Rules\OfficeName;
use App\Solicitor;
use App\SolicitorOffice;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Storage;
use Webpatser\Uuid\Uuid;
use App\AgencySolicitorPanel;
use App\Http\Requests\Onboarding\CreateNewSolicitor;

class SolicitorsController extends Controller
{

    public function index()
    {
        /** @var $user User */
        $user = Auth::user();
        $user_role = $user->userRole()->first();
        $data = [
            'user_role' => $user_role,
        ];
        return view('solicitor.index', $data);
    }

    public function create($solicitorId = null)
    {
        $solicitor = null;
        $view = null;

        if ($solicitorId) {
            $solicitor = loadRecord(new Solicitor, $solicitorId);
            $view = 'solicitor.office.create';
        } else {
            $view = 'solicitor.create';
        }

            $data = [
            'solicitor' => $solicitor,
            ];

            return view($view, $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateNewSolicitor $request)
    {
        try {
            DB::beginTransaction();
            $address = (new Address())->saveAddress($request, 'Solicitor Office');
            if (false === $address) {
                throw new \Exception('Address could not be saved');
            }

            $solicitor = new Solicitor();
            $solicitor->slug = Uuid::generate(4)->string;
            $solicitor->name = $request->name;
            $solicitor->url = $request->url;
            $solicitor->contract_signed = filter_var($request->contract_signed, FILTER_VALIDATE_BOOLEAN);
            $solicitor->save();

            $office = new SolicitorOffice;
            $office->address()->associate($address);
            $office->office_name = !empty($request->office_name) ? $request->office_name : $request->name;
            $office->phone = $request->phone;
            $office->email = $request->email;
            $office->capacity = (int)$request->capacity;
            $office->status = 'Pending';
            $office->referral_fee = $request->referral_fee;

            $solicitor->offices()->save($office);
            $solicitor->default_office = $office->id;
            $solicitor->save();

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();

            return redirect()->back()->with('message', 'Could not save solicitor');
        }

        $image = $request->file('image');
        if (null !== $image) {
            $storagePath = Storage::disk('s3')->put(
                $solicitor->slug . '/' . $office->slug . '/',
                $image,
                'public'
            );

            $office->image_title = $storagePath;
            $office->save();
        }

        return redirect('/solicitors/office/' . $office->slug. '/user/create')
            ->with('message', 'Solicitor Created');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($solicitorId)
    {
        $solicitor = loadRecord(new Solicitor, $solicitorId);

        // get models and send through to view

        $data = [
            'solicitor' => $solicitor,
            'solicitorOffice' => $solicitor->defaultOffice,
            'solicitorAddress' => $solicitor->defaultOffice->address,
        ];

        return view('solicitor.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $solicitor = loadRecord(new Solicitor, $request->solicitor);
        $solicitorOffice = $solicitor->defaultOffice;

        /** @var $user User */
        $user = Auth::user();
        $address = $solicitorOffice->address;
        $address->saveAddress($request, $targetType = false);

        $request->validate([
            'name' => 'required',
            'status' => 'required',
            'contract_signed' => 'required',
        ]);

        $solicitor->name = $request->name;
        $solicitor->contract_signed = $request->contract_signed;
        $solicitor->status = $request->status;
        $solicitor->save();

        $request->validate([
            'office_name' => ['required', new OfficeName($solicitor)],
            'phone' => 'required',
            'capacity' => 'required',
            'tm_ref' => 'required|unique:solicitor_offices,tm_ref,' . $solicitorOffice->id,
            'email' => 'required|unique:solicitor_offices,email,' . $solicitorOffice->id,
            'referral_fee' => 'required|numeric',
        ]);

        $solicitorOffice->office_name = $request->office_name;
        $solicitorOffice->email = $request->email;
        $solicitorOffice->phone = $request->phone;
        $solicitorOffice->tm_ref = $request->tm_ref;
        $solicitorOffice->capacity = $request->capacity;
        $solicitorOffice->status = $request->status;
        $solicitorOffice->save();

        return redirect('/solicitors')
            ->with('message', 'Solicitor Updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($solicitor)
    {
        /** @var $user User */
        $user = Auth::user();
        Solicitor::destroy($solicitor);
        return redirect('/solicitors')->with('message', 'Solicitor Deleted');
    }

    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRecords()
    {
        /** @var $user User */
        $user = Auth::user();
        $solicitorModel = new Solicitor;
        $solicitors = $solicitorModel->getSolicitorMarketDetails();
        return Datatables::of($solicitors)->make(true);
    }

    public function getOfficesForSolicitor(Request $request)
    {
        /** @var $user User */
        $user = Auth::user();
        $solicitorId = $request['solicitorId'];
        $offices = Solicitor::find($solicitorId)->offices;
        $html = "";

        if (!$offices->isEmpty()) {
            $html .= '<option value="">Please select' . ' (' . count($offices) . ')' . '</option>';
            foreach ($offices as $office) {
                $html .= '<option class="ajax-options" value="' .
                    $office->id .
                    '">' .
                    $office->office_name .
                    '</option>';
            }
        } else {
            $html .= '<option value="">No offices for this solicitor</option>';
        }

        return response()->json([
            "success" => true,
            "data" => [
                'options' => $html
            ]
        ]);
    }

    public function updateKpis(Request $request)
    {
        /** @var $user User */
        $user = Auth::user();

        $statuses = [
            'completed',
            'aborted',
            'instructed',
            'instructed_unpanelled'
        ];

        $filters = [
            ['active', '=', 1],
            ['date_created', '>=', strtotime('midnight first day of this month')],
            ['agency_id', '=', $user->agencyUser->agency_id],
        ];

        if ($request->solicitor_id !== 'all') {
            $filters[] = ['solicitor_id', '=', $request->solicitor_id];
        }

        $count = [];
        foreach ($statuses as $status) {
            $filterCopy = $filters;
            $filterCopy[] = ['status', '=', $status];

            $queryCount =
                ConveyancingCase::where($filterCopy)
                ->count();

            $count[$status] = $queryCount;
        }

        return response()->json([
            'success' => true,
            'data' => [
                'count' => $count
            ]
        ]);
    }
}
