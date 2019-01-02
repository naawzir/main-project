<?php

namespace App\Http\Controllers;

use App\Agency;
use App\Note;
use App\AgencyBranch;
use App\TargetsAgencyBranch;
use App\Notifications\AgencyBranchContact;
use App\User;
use App\StaffUser;
use App\Solicitor;
use App\SolicitorOffice;
use App\SolicitorUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use DB;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Contracts\Encryption\DecryptException;
use Yajra\Datatables\Datatables;

class SolicitorPerformanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        $view = 'solicitor.performance.index';

        $date = strtotime('midnight first day of this month');
        $solicitorModel = new Solicitor;
        $kpis = $solicitorModel->currentPerformanceKPIs($date);

        $data = [

            'user' => $user,
            'kpis' => (object) $kpis,
        ];

        return view($view, $data);
    }

    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSolicitorsStatsRecords()
    {
        /** @var $user User */
        $user = Auth::user();
        $solicitorModel = new Solicitor;
        $solicitorsStats = $solicitorModel->getSolicitorsStatsRecords();

        return Datatables::of($solicitorsStats)->make(true);
    }

    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSolicitorStatsRecords($solicitorId)
    {
        $solicitor = loadRecord(new Solicitor, $solicitorId);

        /** @var $user User */
        $user = Auth::user();
        $solicitorModel = new Solicitor;
        $solicitorStats = $solicitorModel->getSolicitorStatsRecords($solicitor->id);

        return Datatables::of($solicitorStats)->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $solicitor = loadRecord(new Solicitor, $request->solicitor);

        $user = Auth::user();

        $date = strtotime('midnight first day of this month');
        $solicitorModel = new Solicitor;
        $kpis = $solicitorModel->currentPerformanceKPIs($date);

        $data = [
            'user' => $user,
            'solicitor' => $solicitor,
            'kpis' => (object) $kpis,
        ];

        $view = 'solicitor.performance.show';

        return view($view, $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    public function getSolicitorPerformanceData($from, $branchId) : array
    {
        $data = [
            'capacityRemaining' => $this->capacityRemaining(),
        ];

        return $data;
    }

    private function capacityRemaining()
    {
        return 3;
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
