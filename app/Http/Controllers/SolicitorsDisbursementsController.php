<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Disbursement;
use Illuminate\Http\Request;
use App\SolicitorOffice;
use App\SolicitorDisbursement;
use App\User;

class SolicitorsDisbursementsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($officeId)
    {
        $solicitorOffice = loadRecord(new SolicitorOffice, $officeId);
        $solicitor = $solicitorOffice->solicitor;
        $solicitorAddress = $solicitorOffice->address;
        $disbursements = $solicitorOffice->disbursements->pluck('disbursement_id');
        $data = [
            'solicitor' => $solicitor,
            'solicitorOffice' => $solicitorOffice,
            'solicitorAddress' => $solicitorAddress,
            'disbursements' => $disbursements,
        ];
        return view('solicitor.office.disbursements', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  string  $officeId
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update($officeId, Request $request)
    {
        /** @var $user User */
        $user = Auth::user();
        $solicitorOffice = loadRecord(new SolicitorOffice, $officeId);
        $length = count($request->id);
        $successCount = 0;
        $errorCount = 0;
        $oldDisbursements = $solicitorOffice->disbursements->pluck('disbursement_id');

        foreach ($oldDisbursements as $disbursement) {
            self::destroyLink($disbursement, $solicitorOffice->id);
        }

        for ($i = 0; $i < $length; $i++) {
            $solDisp = new SolicitorDisbursement;
            $solDisp->solicitor_office_id = $solicitorOffice->id;
            $solDisp->disbursement_id = $request->id[$i];

            if ($solDisp->save()) {
                $successCount++;
            } else {
                $errorCount++;
            }
        }

        return redirect('/solicitors/office/' . $officeId)
            ->with('message', $successCount . ' disbursements added and ' . $errorCount . ' errors');
    }

    private function destroyLink($disid, $officeID)
    {
        if (SolicitorDisbursement::where([
            ['disbursement_id', $disid],
            ['solicitor_office_id', $officeID]
            ])->delete()) {
            return true;
        }

        return false;
    }
}
