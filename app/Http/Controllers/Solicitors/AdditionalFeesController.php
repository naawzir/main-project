<?php

namespace App\Http\Controllers\Solicitors;

use App\Address;
use App\LegalFee;
use App\AdditionalFee;
use App\Http\Controllers\Controller;
use App\Http\Requests\ChangeAdditionalFees;
use App\Solicitor;
use App\SolicitorOffice;
use App\SolicitorUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Webpatser\Uuid\Uuid;

class AdditionalFeesController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(SolicitorOffice $office, Request $request)
    {
        $solicitor = $office->solicitor;
        $solicitorAddress = $office->address;
        $solDefOffice = $solicitor->defaultOffice;
        // Primary office additional fees
        $priOfficeAddFees = $solDefOffice->additionalFees;

        $data = [
            'solicitor' => $solicitor,
            'solicitorOffice' => $office,
            'solicitorAddress' => $solicitorAddress,
            'priOfficeAddFees' => $priOfficeAddFees,
        ];

        return view('solicitor.office.additional-fees', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param SolicitorOffice $office
     * @param ChangeAdditionalFees $request
     * @return \Illuminate\Http\Response
     */
    public function store(SolicitorOffice $office, ChangeAdditionalFees $request)
    {
        $fee = new AdditionalFee;
        $fee->mortgage = $request->mortgage;
        $fee->mortgage_redemption = $request->mortgage_redemption;
        $fee->leasehold = $request->leasehold;
        $fee->archive = $request->archive;

        if ($office->additionalFees()->save($fee)) {
            // Redirect
            return redirect('/solicitors/office/' . $office->slug . '/fee-structures/create/')
                ->with('message', 'Solicitor Office Additional Fees Added');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(SolicitorOffice $office, Request $request)
    {
        $additionalFee = $office->additionalFees;
        $solicitor = $office->solicitor;

        $data = [
            'additionalFee' => $additionalFee,
            'solicitorOffice' => $office,
            'solicitor' => $solicitor,
        ];

        return view('solicitor.office.edit-additional-fees', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param SolicitorOffice $office
     * @param ChangeAdditionalFees $request
     * @return \Illuminate\Http\Response
     */
    public function update(SolicitorOffice $office, ChangeAdditionalFees $request)
    {
        $fee = $office->additionalFees;

        $fee->mortgage = $request->mortgage;
        $fee->mortgage_redemption = $request->mortgage_redemption;
        $fee->leasehold = $request->leasehold;
        $fee->archive = $request->archive;
        $fee->save();

        if ($fee->save()) {
            $office = $fee->solicitorOffice;
            // Redirect
            return redirect('/solicitors/office/' . $office->slug)
                ->with('message', 'Solicitor Office Fees Updated');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
