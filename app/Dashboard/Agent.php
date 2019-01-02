<?php declare(strict_types=1);

namespace App\Dashboard;

use App\User;
use App\ConveyancingCase;
use Illuminate\Support\Facades\Auth;
use App\FeedbackCustomerForTcp;
use App\FeedbackAgentForTcp;

class Agent implements Dashboard
{
    public function getData(User $user): array
    {
        $data = [
            'caseKpiFigures' => $this->caseKpiFigures(),
            "monthlyFeedbackLastCompleted" => $this->monthlyFeedbackLastCompleted(),
            'activecases' => $this->agentActiveCases()
        ];

        return $data;
    }

    private function caseKpiFigures()
    {
        $date = strtotime('midnight first day of this month');

        $casesModel = new ConveyancingCase;
        return $casesModel->casesKpiFigures(
            $branchIds = false,
            $userIdAgent = Auth::user()->id,
            $date
        );
    }

    public function getTarget(User $user)
    {
        // get monthly target

        // break that down in to daily taget as well

        return [];
    }

    public function getInstructions(User $user, $from = null)
    {
        return [];
    }

    private function getInstructionsData($user, $date)
    {
        return [];
    }

    public function getCurrentActivity($from = null)
    {
        return [];
    }

    public function getTasks(User $user)
    {
        // get all users Tasks
        return [];
    }

    public function monthlyFeedbackLastCompleted()
    {
        $feedback = new FeedbackAgentForTcp;

        return $feedback->where([
            ["user_id_agent", "=", Auth::user()->id],
        ])->orderBy('date_created', 'desc')->first();
    }

    public function agentActiveCases()
    {
        $date = strtotime('midnight first day of this month');

        $user = Auth::user();
        $userAgent = $user->agencyUser;
        $caseModel = new ConveyancingCase;
        return $caseModel->getCasesForAgencyUsers($date, $user, $userAgent, $userIdAgent = true);
    }
}
