<?php

namespace App\Http\Controllers;

use App\ConveyancingCase;
use App\ConveyancingCasesHistory;
use App\Agency;
use App\AgencyBranch;
use App\Address;
use App\Model;
use App\FeedbackAgentForTcp;
use App\FeedbackAgentForSolicitorOffice;
use App\FeedbackCustomerForSolicitorOffices;
use App\LegalFee;
use App\Solicitor;
use App\SolicitorOffice;
use App\FeedbackCustomerForTcp;
use App\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use Webpatser\Uuid\Uuid;
use App\Dashboard\BusinessOwner;

class FeedbackController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $casesModel = new ConveyancingCase;
        $cases = $casesModel->where([
            ['date_created', '>=', strtotime('midnight first day of this month')],
            ['user_id_agent', '=', Auth::user()->id],
        ])
            ->groupBy('solicitor_office_id')
            ->get();

        $data = [
            'cases' => $cases,
        ];

        return view('agent.feedback-form', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'score' => 'required',
            'solicitors' => 'sometimes|array',
            'solicitors.*' => 'required|numeric|between:1,10',
        ]);
        $user = $request->user();

        try {
            DB::beginTransaction();
            $feedbackAgentsForTcp = new FeedbackAgentForTcp();
            $feedbackAgentsForTcp->user_id_agent = $user->id;
            $feedbackAgentsForTcp->score = $request->score;
            $feedbackAgentsForTcp->comments = $request->comments;
            $feedbackAgentsForTcp->save();

            foreach ($request->solicitors ?? [] as $solicitorOfficeId => $score) {
                /** @var SolicitorOffice $office */
                $office = loadRecord(new SolicitorOffice, $solicitorOfficeId);
                $office->agentFeedback()->create([
                    'user_id_agent' => $user->id,
                    'score' => $score,
                ]);
            }
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();

            return redirect()->back()->withInput()->with('error', 'Unable to save feedback');
        }

        return redirect('/')->with('message', 'Feedback created');
    }

    public function serviceFeedback()
    {
        $custFBForOffices = new FeedbackCustomerForSolicitorOffices;
        $agentFBForTCP = new FeedbackAgentForTcp;

        $data = [

            // Customer Feedback On Solicitor
            'customerHighScoreCount' => $custFBForOffices->getHighScoresCount(),
            'customerLowScoreCount' => $custFBForOffices->getLowScoresCount(),
            'customerFeedbackAverageTCP' => $custFBForOffices->getAverage(),
            'customerFeedbackAverageTCPDonut' => $custFBForOffices->getAverageDonut(),
            'customerResponseRate' => $custFBForOffices->getCustomerFeedbackResponseRate(),

            // Staff Feedback On Account Manager
            'agentHighScoreCount' => $agentFBForTCP->getHighScoresCount(),
            'agentLowScoreCount' => $agentFBForTCP->getLowScoresCount(),
            'agentFeedbackAverageTCPDonut' => $agentFBForTCP->getAverageDonut(),
            'agentFeedbackAverageTCP' => $agentFBForTCP->getAverage(),
            'agentResponseRate' => $agentFBForTCP->getAgentFeedbackResponseRate(),
        ];

        return view('business-owner.service-feedback', $data);
    }

    public function lowScoringCustomerFeedback()
    {
        $custFBForOffices = new FeedbackCustomerForSolicitorOffices;
        $feedback = $custFBForOffices->lowScoringCustomerFeedback();
        return Datatables::of($feedback)->make(true);
    }
}
