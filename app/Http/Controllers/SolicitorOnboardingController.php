<?php

namespace App\Http\Controllers;

use App\Solicitor;
use App\SolicitorOffice;
use Illuminate\Support\Facades\Auth;

class SolicitorOnboardingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $user_role = $user->userRole()->first();
        $isBDM = $user_role->dashboard_title === 'bdm';
        $solicitorOffices = SolicitorOffice::onboarding($isBDM)->get();

        $data = [
            'offices' => $solicitorOffices,
            'isBDM' => $isBDM,
        ];

        return view('onboarding.solicitors', $data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($solicitorId)
    {
        $solicitor = loadRecord(new Solicitor, $solicitorId);
        $user = Auth::user();
        $user_role = $user->userRole()->first();
        $isBDM = $user_role->dashboard_title === 'bdm';
        $query = $isBDM ? $solicitor->offices->whereIn('status', ['Pending']) :
            $solicitor->offices->whereIn('status', ['Onboarding', 'SentToTM']);
        $solicitorOffices = $query;
        $data = [
            'solicitor' => $solicitor,
            'solicitorOffices' => $solicitorOffices,
        ];

        return view('onboarding.offices', $data);
    }

    public function getOnboarding()
    {
        $user = Auth::user();
        $userRole = $user->userRole()->first();

        return response()->json([
            'success' => true,
            'data' => [
                'count' => Solicitor::onboarding($userRole->dashboard_title === 'bdm')->count(),
            ]
        ]);
    }
}
