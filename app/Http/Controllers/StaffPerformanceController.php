<?php

namespace App\Http\Controllers;

use Auth;
use App\StaffUser;
use App\Transaction;
use App\ConveyancingCase;
use Illuminate\Http\Request;

class StaffPerformanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $staffUser = new StaffUser;
        $accountMans = $staffUser->getAccMans();
        $viewData = $this->show();

        $data = [
            'accountMans' => $accountMans,
            'viewData' => $viewData
        ];
        return view('staff.index', $data);
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
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $user = false;
        $date = strtotime('midnight first day of this month');
       
        $data =[
            'performance' => $this->getPerformance($date, $user),
            'currentActivity' => $this->getCurrentActivity($date, $user),
            'conversion' => $this->getConversion($date, $user),
            'prospects' => $this->getProspects($date, $user)
        ];

        return $data;
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
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $data = json_decode($request['data']);
        $user = false;
        $date = strtotime('first day of this month');

        foreach ($data as $filter) {
            foreach ($filter as $key => $value) {
                if ($key === 'date') {
                    $date = $value;
                } elseif ($key === 'user') {
                    $user = $value;
                }
            }
        }

        $response = array(
            'performance' => $this->getPerformance($date, $user),
            'currentActivity' => $this->getCurrentActivity($date, $user),
            'conversion' => $this->getConversion($date, $user),
            'prospects' => $this->getProspects($date, $user)
        );

        return response()->json([
            'success' => true,
            'response' => $response
        ]);
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

    /**
     * Get the Performance statistics for all a specific user
     */
    private function getPerformance($date, $user = false)
    {
        // target

        // instructions MTD

        // daily MTD
        return [];
    }

     /**
     * Get the Current Activity
     */
    private function getCurrentActivity($date, $user = false)
    {
       // get snapshot of total stats
        $new = Transaction::where([
            ['active', '=', 1],
            ['date_created', '>=', $date],
            ['status', '=', 'prospect']
        ])->count();

        $unpanelled = Transaction::where([ // Transaction should be ConveyancingCase
            ['active', '=', 1],
            ['date_created', '>=', $date],
            ['status', '=', 'unpanelled']
        ])->count();

        $completions = Transaction::where([
            ['active', '=', 1],
            ['date_created', '>=', $date],
            ['status', '=', 'completed']
        ])->count();

        $aborted = Transaction::where([ // Transaction should be ConveyancingCase
            ['active', '=', 0],
            ['date_created', '>=', $date],
            ['status', '=', 'aborted']
        ])->count();

        return [
            'new' => $new,
            'unpanelled' => $unpanelled,
            'completions' => $completions,
            'aborted' => $aborted
        ];
    }

     /**
     * Get the Conversion statistics for all a specific user
     */
    private function getConversion($date, $user = false)
    {
        /*
            the conversion will need to look at the Conveyancing Case History table
            it will need to get the amount of instructions created (by user or date)
            it will also need to get the amount of those that have since been instructed
        */
        return [];
    }

     /**
     * Get the Prospects statistics for all a specific user
     */
    private function getProspects($date, $user = false)
    {

        return[];
    }
}
