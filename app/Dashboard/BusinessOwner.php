<?php declare(strict_types=1);

namespace App\Dashboard;

use App\AgencyBranch;
use App\User;
use App\Transaction;
use App\ConveyancingCase;
use App\FeedbackAgentForTcp;
use App\FeedbackCustomerForSolicitorOffices;

class BusinessOwner implements Dashboard
{
    public function getData(User $user): array
    {
        $data = [
            'kpis' => (object) $this->kpisAjax(),
            'branches' => (object) $this->businessOwnerBranches(),
            'feedbackTotal' => $this->getFeedbackTotal(),
            'agentFeedbackAverageTCP' => $this->getStaffFeedbackAverage(),
            'customerFeedbackAverageTCP' => $this->getCustomerFeedbackAverage(),
            'lowScoringFeedback' => $this->getLowScoringFeedback()
        ];

        return $data;
    }

    private function getFeedbackTotal()
    {
        $custFeedbackCount = $this->getCustomerFeedbackOnTCP();
        $agentFeedbackCount = $this->getStaffFeedbackTotal();
        $total = $custFeedbackCount + $agentFeedbackCount;
        return $total;
    }

    private function getCustomerFeedbackOnTCP()
    {
        $feedbackModel = new FeedbackCustomerForSolicitorOffices();
        return $feedbackModel->getFeedbackCount();
    }

    private function getStaffFeedbackTotal()
    {
        $feedbackModel = new FeedbackAgentForTcp();
        return $feedbackModel->getFeedbackCount();
    }

    private function getStaffFeedbackAverage()
    {
        $feedbackModel = new FeedbackAgentForTcp;
        return $feedbackModel->getAverage();
    }

    private function getCustomerFeedbackAverage()
    {
        $feedbackModel = new FeedbackCustomerForSolicitorOffices;
        return $feedbackModel->getAverage();
    }

    private function businessOwnerBranches()
    {
        $branchModel = new AgencyBranch;
        $branches = $branchModel->businessOwnerBranches();
        return $branches;
    }

    private function branchesFilterOptionsBOwner($branches)
    {
        $branchArray = [];
        foreach ($branches as $branch) {
            $branchArray[$branch->Branch] = $branch->id;
        }
        return $branchArray;
    }

    private function kpisAjax()
    {
        $dateFrom = strtotime('midnight first day of this month');
        $dateTo = time();
        $kpis = null;
        $branchModel = new AgencyBranch;
        $branches = $branchModel->businessOwnerBranches();
        $branchIds = $this->branchesFilterOptionsBOwner($branches);
        $kpis = $branchModel->branchPerformanceKpiCount($branchIds, $dateFrom, $dateTo);
        return $kpis;
    }

    private function getLowScoringFeedback()
    {
        $FBCustForTcp = new FeedbackCustomerForSolicitorOffices;
        $FBAgentForTCP = new FeedbackAgentForTcp;
        $custLowScoreCnt = $FBCustForTcp->getLowScoresCount();
        $agentLowScoreCnt = $FBAgentForTCP->getLowScoresCount();
        return $custLowScoreCnt + $agentLowScoreCnt;
    }
}
