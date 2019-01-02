<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Disbursement;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Webpatser\Uuid\Uuid;
use Illuminate\Support\Facades\DB;

class DisbursementsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('disbursements.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    // currently not being used
    public function create()
    {
        return view('disbursements.create');
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

        $request->validate([
            'name' => 'required|unique:third_party_disbursements',
            'transaction' => 'required',
            'type' => 'required',
            'cost' => 'required|numeric',
        ]);

        try {
            DB::beginTransaction();
            $disbursement = new Disbursement();
            $disbursement->slug = Uuid::generate(4)->string;
            $disbursement->name = $request->name;
            $disbursement->transaction = $request->transaction;
            $disbursement->type = $request->type;
            $disbursement->cost = $request->cost;
            $disbursement->active = 1;
            $disbursement->save();
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Unable to save disbursement');
        }

        if (!empty($request->return)) {
            return redirect(
                '/solicitors/office/' .
                $request->return .
                '/disbursements'
            )->with('message', 'Disbursement Added.');
        } else { // a third party disbursement being added (not a solicitor office disbursement
            return redirect()->route('disbursement-home')->with('message', 'Disbursement Added');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($disbursementId)
    {
        $disbursement = loadRecord(new Disbursement, $disbursementId);

        $data = [
            'disbursement' => $disbursement,
        ];

        return view('disbursements.edit', $data);
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

        $disbursement = loadRecord(new Disbursement, $request->disbursement);

        $request->validate([
            'name' => 'required|unique:third_party_disbursements,name,' . $disbursement->id,
            'transaction' => 'required',
            'type' => 'required',
            'cost' => 'required|numeric',
        ]);

        $disbursement->name = $request->name;
        $disbursement->transaction = $request->transaction;
        $disbursement->type = $request->type;
        $disbursement->cost = $request->cost;

        if ($disbursement->save()) {
            // redirect
            return redirect('/disbursements')->with('message', 'Disbursement Updated.');
        }

        return redirect('/disbursements')
            ->withErrors(['errors' => 'Could Not Update The Disbursement.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $disbursement = loadRecord(new Disbursement, $request->disbursementId);
        if (Disbursement::destroy($disbursement->id)) {
            return redirect()->route('disbursement-home')
                ->with('message', 'Disbursement Deleted');
        }
    }

    public function getDisbursements()
    {
        /** @var $user User */
        $user = Auth::user();
        $disbursementModel = new Disbursement;
        $disbursements = $disbursementModel->getDisbursements();
        return Datatables::of($disbursements)->make(true);
    }
}
