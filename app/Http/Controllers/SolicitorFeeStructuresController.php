<?php

namespace App\Http\Controllers;

use App\LegalFee;
use App\SolicitorOffice;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Webpatser\Uuid\Uuid;

class SolicitorFeeStructuresController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $solicitorOffice = loadRecord(new SolicitorOffice, $request->solicitorOfficeId);

        $solicitor = $solicitorOffice->solicitor;
        $solicitorAddress = $solicitorOffice->address;
        $defaultOffice = '';
        $feeStrPurchase = '';
        $feeStrSale = '';

        if ($solicitorOffice->id != $solicitor->default_office) {
            $defaultOffice = loadModel(new SolicitorOffice, $solicitor->default_office);
            $feeStrPurchase = $defaultOffice->feeStructures->where('case_type', '=', 'purchase');
            $feeStrSale = $defaultOffice->feeStructures->where('case_type', '=', 'sale');
        }

        $data = [
            'solicitor' => $solicitor,
            'solicitorOffice' => $solicitorOffice,
            'solicitorAddress' => $solicitorAddress,
            'defaultOffice' => $defaultOffice,
            'feeStrPurchase' => $feeStrPurchase,
            'feeStrSale' => $feeStrSale,
        ];

        return view('solicitor.office.fee-structures', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /** @var $user User */
        $user = Auth::user();
        $solicitorOffice = loadRecord(new SolicitorOffice, $request->solicitorOfficeId);
        $length_submit = count($request->legal_fee);
        $length_sp = count($request->legal_fee_sale_purchase);
        $caseTypes = ['purchase', 'sale'];

        if (!empty($request->case_type_sale_purchase) && $request->case_type_sale_purchase === 'salePurchase') {
            for ($i = 0; $i < $length_sp; $i++) {
                foreach ($caseTypes as $caseType) {
                    $feeStructure = new LegalFee();
                    $feeStructure->slug = Uuid::generate(4)->string;
                    $feeStructure->price_from = $request->price_from_sale_purchase[$i];
                    $feeStructure->price_to = $request->price_to_sale_purchase[$i];
                    $feeStructure->legal_fee = $request->legal_fee_sale_purchase[$i];
                    $feeStructure->case_type = $caseType;
                    $feeStructure->active = 1;
                    $feeStructure->solicitor_office_id = $solicitorOffice->id;
                    $feeStructure->save();
                }
            }
        } else {
            for ($i = 0; $i < $length_submit; $i++) {
                $feeStructure = new LegalFee();
                $feeStructure->slug = Uuid::generate(4)->string;
                $feeStructure->price_from = $request->price_from[$i];
                $feeStructure->price_to = $request->price_to[$i];
                $feeStructure->legal_fee = $request->legal_fee[$i];
                $feeStructure->referral_fee = $request->referral_fee[$i];
                $feeStructure->case_type = $request->case_type[$i];
                $feeStructure->active = 1;
                $feeStructure->solicitor_office_id = $solicitorOffice->id;
                $feeStructure->save();
            }
        }

        if ($solicitorOffice->disbursements->pluck('disbursement_id')->count() == 0) {
            $url = '/solicitors/office/' . $solicitorOffice->slug . '/disbursements';
        } else {
            $url = '/solicitors/office/' . $solicitorOffice->slug;
        }

        // Redirect
        return redirect($url)
            ->with('message', 'Solicitor Office Pricing Structures Added');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $solicitorOffice = loadRecord(new SolicitorOffice, $request->solicitorOfficeId);
        $feeStrPurchase = $solicitorOffice->feeStructures->where('case_type', '=', 'purchase');
        $feeStrSale = $solicitorOffice->feeStructures->where('case_type', '=', 'sale');

        $data = [
            'feeStrPurchase' => $feeStrPurchase,
            'feeStrSale' => $feeStrSale,
            'solicitorOffice' => $solicitorOffice,
        ];

        return view('solicitor.office.edit-fee-structure', $data);
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
        /** @var $user User */
        $user = Auth::user();
        $solicitorOffice = loadRecord(new SolicitorOffice, $request->solicitorOfficeId);
        $prf_length = count($request->price_from);

        for ($i = 0; $i < $prf_length; $i++) {
            if ($request->id[$i] != '') {
                $feeStructure = LegalFee::find($request->id[$i]);
            } else {
                $feeStructure = new LegalFee();
                $feeStructure->slug = Uuid::generate(4)->string;
                $feeStructure->solicitor_office_id = $solicitorOffice->id;
            }
            $feeStructure->price_from = $request->price_from[$i];
            $feeStructure->price_to = $request->price_to[$i];
            $feeStructure->legal_fee = $request->legal_fee[$i];
            $feeStructure->referral_fee = $request->referral_fee[$i];
            $feeStructure->case_type = $request->case_type[$i];
            $feeStructure->active = 1;
            $feeStructure->save();
        }

        // Redirect
        return redirect('/solicitors/office/' . $solicitorOffice->slug)
            ->with('message', 'Solicitor Office Fee Structure(s) Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $feeStructure = loadRecord(new LegalFee, $request->feeStructureId);

        /** @var $user User */
        $user = Auth::user();
        if (LegalFee::destroy($feeStructure->id)) {
            return response()->json(
                [
                    'success' => true,
                    'message' => 'Record Deleted'
                ]
            );
        }

        return response()->json(
            [
                'success' => false,
                'message' => 'Could Not Find Record'
            ]
        );
    }

    public function getFeeStructures(Request $request)
    {
        $solicitorOffice = loadRecord(new SolicitorOffice, $request->solicitorOfficeId);

        /** @var $user User */
        $user = Auth::user();
        $feeStructures =
            $solicitorOffice->feeStructures()
                ->orderby('case_type')
                ->orderby('price_from');
        return Datatables::of($feeStructures)->make(true);
    }
}
