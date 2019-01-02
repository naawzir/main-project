<?php

namespace App\Http\Controllers;

use App\Solicitor;
use App\SolicitorOffice;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\AgencySolicitorPanel;
use Illuminate\Support\Facades\DB;

class MySolicitorController extends Controller
{
    public function index()
    {
        /** @var $user User */
        $user = Auth::user();

        $solicitorPanelModel = new AgencySolicitorPanel;
        $Solicitor = new Solicitor;
        $mySolicitors = $Solicitor->getMySolicitors();
        $panelSolicitors = $solicitorPanelModel
            ->where('agency_id', $user->agencyUser->agency_id)
            ->get();

        $solicitoroffices = [];
        foreach ($panelSolicitors as $panelSolicitor) {
            $solicitoroffices[] = SolicitorOffice::find($panelSolicitor->solicitor_office_id)->solicitor;
        }

        $statuses = [
            'completed',
            'aborted',
            'instructed',
            'instructed_unpanelled'
        ];

        $count = [];
        foreach ($statuses as $status) {
            $queryCount = DB::table('conveyancing_cases as c')
                ->join('service_collections as sc', 'sc.target_id', '=', 'c.id')
                ->join('transaction_service_collections as tsc', 'tsc.service_collection_id', '=', 'sc.id')
                ->join('transactions as t', 't.id', '=', 'tsc.transaction_id')
                ->select([
                    DB::raw('c.id')
                ])
                ->where([
                    ['c.active', '=', 1],
                    ['c.status', '=', $status],
                    ['c.date_created', '>=', strtotime('midnight first day of this month')],
                    ['t.agency_id', '=', $user->agencyUser->agency_id]
                ])
                ->count();

            $count[$status] = $queryCount;
        }

        $data = [
            'solicitors' => $solicitoroffices,
            'statusCount' => (object) $count,
            'mySolicitors' => $mySolicitors
        ];

        return view('business-owner.my-solicitors', $data);
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

    public function show(SolicitorOffice $office)
    {
        $solicitor = $office->solicitor;

        $fees = $office->feeStructures;
        $additionalFees = $office->additionalFees;
        $purchaseFees = $fees->where('case_type', 'Purchase')->sortBy('price_from');
        $saleFees = $fees->where('case_type', 'Sale')->sortBy('price_from');
        $partnership = $this->solicitorDetailedView($office->id);

        return view('panel.office', [
            'solicitor' => $solicitor,
            'office' => $office,
            'additionalFees' => $additionalFees,
            'purchaseFees' => $purchaseFees,
            'saleFees' => $saleFees,
            'partnership' => $partnership,
        ]);
    }

    public function addToPanel(Request $request)
    {
        $slug = $request->officeid;

        /** @var $solicitorOffice SolicitorOffice */
        $solicitorOffice = loadRecord(new SolicitorOffice, $slug);
        $user = Auth::user();
        $agencyId = $user->agencyUser->agency_id;

        try {
            DB::beginTransaction();
            $agencyPanelId = new AgencySolicitorPanel;
            $agencyPanelId->agency_id = $agencyId;
            $agencyPanelId->solicitor_office_id = $solicitorOffice->id;
            $agencyPanelId->save();

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            dd($e);
            return redirect()->back()->with('errors', 'Solicitor Office could not be added to the panel');
        }

        return redirect('/my-solicitors')->with('message', 'Solicitor Office added to the panel');
    }

    // this deletes the partnership between the Agency and the Solicitor Office (removes them from the panel)
    public function removeFromPanel(Request $request)
    {
        $slug = $request->officeid;

        /** @var $solicitorOffice SolicitorOffice */
        $solicitorOffice = loadRecord(new SolicitorOffice, $slug);
        $user = Auth::user();
        $agencyId = $user->agencyUser->agency_id;

        try {
            DB::beginTransaction();
            $agencyPanelId = new AgencySolicitorPanel;
            $agencyPanelId->where([
                ['solicitor_office_id', '=', $solicitorOffice->id],
                ['agency_id', '=', $agencyId]
            ])->delete();

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();

            return redirect()->back()->with('errors', 'Solicitor Office could not be removed from the panel');
        }

        return redirect('/my-solicitors')->with('message', 'Solicitor Office removed from the panel');
    }
}
