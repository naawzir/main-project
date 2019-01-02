<?php

namespace App;

use Auth;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class FeedbackAgentForTcp extends Model implements AuditableContract
{
    use Auditable;

    protected $table = 'feedback_agents_for_tcp';

    public function getAverage()
    {
        $count = $this->where([
            ['score', '!=', ''],
            ['date_created', '>=', strtotime('midnight first day of this month')],
        ])
        ->whereIn('user_id_agent', $this->getAgents())
        ->avg('score');
        
        return $count;
    }

    public function getAverageDonut()
    {
        $count = $this->where([
            ['score', '!=', ''],
            ['date_created', '>=', strtotime('midnight first day of this month')],
        ])
        ->whereIn('user_id_agent', $this->getAgents())
        ->avg('score');

        $total = 10;
        $completed = !empty($total) ? 10 / $total * $count : 0;
        $completed = round($completed, 1);
        $notCompleted = 10 - $completed;

        return [
            'AverageScoreAgent' => [
                [
                    'amount' => $completed,
                    'color' => '#B7C582',
                    'desc' => 'Average Score'
                ],
                [
                    'amount' => $notCompleted,
                    'color' => '#d8d6d7'
                ]
            ]
        ];
    }

    public function getFeedbackCount()
    {
        $count = $this->where([
            ['score', '!=', ''],
            ['date_created', '>=', strtotime('midnight first day of this month')],
        ])
        ->whereIn('user_id_agent', $this->getAgents())
        ->count();

        return $count;
    }

    public function getHighScoresCount()
    {
        $count = $this->where([
            ['score', '!=', ''],
            ['score', '>', 5],
            ['date_created', '>=', strtotime('midnight first day of this month')],
        ])
        ->whereIn('user_id_agent', $this->getAgents())
        ->count();

        return $count;
    }

    public function getLowScoresCount()
    {
        $count = $this->where([
            ['score', '!=', ''],
            ['score', '<', 6],
            ['date_created', '>=', strtotime('midnight first day of this month')],
        ])
        ->whereIn('user_id_agent', $this->getAgents())
        ->count();

        return $count;
    }

    public function getAgentFeedbackResponseRate()
    {
        $nonCompletedFeedback = $this->where([
            ['date_created', '>=', strtotime('midnight first day of this month')],
        ])
        ->whereIn('user_id_agent', $this->getAgents())
        ->count();

        $completedFeedback = $this->where([
            ['date_created', '>=', strtotime('midnight first day of this month')],
        ])
        ->whereIn('user_id_agent', $this->getAgents())
        ->count();

        $total = $nonCompletedFeedback + $completedFeedback;
        $completed = !empty($total) ? round(100 / $total * $completedFeedback) : 0;
        $notCompleted = 100 - $completed;

        return [
            'responseRateAgent' => [
                [
                    'amount' => $completed,
                    'color' => '#b7c582',
                    'desc' => 'Response Rate'
                ],
                [
                    'amount' => $notCompleted,
                    'color' => '#d8d6d7'
                ]
            ]
        ];
    }

    // this is used for the Service Feedback page
    private function getAgents()
    {
        $user = Auth::User();
        $agencyId = $user->agencyUser->agency_id;

        if (!empty($agencyId)) {
            $branches = Agency::find($agencyId)->branches;
            foreach ($branches as $branch) {
                $agencyBranchUsers = AgencyBranch::find($branch->id)->agentUsers;
            }

            $userAgentIds = $agencyBranchUsers->pluck('id')->toArray();
            return $userAgentIds;
        }

        return false;
    }
}
