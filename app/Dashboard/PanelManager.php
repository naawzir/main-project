<?php declare(strict_types=1);

namespace App\Dashboard;

use App\AgentUpdateRequest;
use App\User;
use App\Task;
use App\FeedbackCustomerForTcp;
use App\FeedbackAgentForSolicitorOffice;

class PanelManager implements Dashboard
{
    public function getData(User $user): array
    {
        $data =
        [
            'alertTasksCount' => $this->getAlertTasksCount(),
            'reminderTasksCount' => $this->getReminderTasksCount(),
            'customerFeedbackAverage' => $this->getCustomerFeedbackAverage(),
            'agentFeedbackAverage' => $this->getAgentFeedbackAverage(),
            'feedbackTotal' => $this->getFeedbackTotal(),
            'updateRequestsTotal' => $this->getUpdateRequestsTotal(),
        ];

        return $data;
    }

    private function getFeedbackTotal() // not completed
    {
        $custFeedbackCount = $this->getCustomerFeedbackCount();
        $agentFeedbackCount = $this->getAgentFeedbackCount();
        $total = $custFeedbackCount + $agentFeedbackCount;
        return $total;
       /* return [
            'feedbackOnSolicitors' => [
                [
                    'amount' => $total,
                    'color' => '#919D66',
                    'desc' => 'Total'
                ],
                [
                    'amount' => $agentFeedbackCount,
                    'color' => '#B7C582'
                ]
            ],
        ];*/
    }

    private function getUpdateRequestsTotal()
    {
        $agentUpdateRequest = new AgentUpdateRequest;
        return $agentUpdateRequest->getAgentUpdateRequestCount();
    }

    private function getReminderTasksCount()
    {
        $taskModel = new Task;
        return $reminderTasks = $taskModel->countTasks('reminder');
    }

    private function getAlertTasksCount()
    {
        $taskModel = new Task;
        return $alertTasks = $taskModel->countTasks('alert');
    }

    private function getCustomerFeedbackAverage()
    {
        $feedbackModel = new FeedbackCustomerForTcp;
        return $feedbackModel->getAverage();
    }

    private function getAgentFeedbackAverage()
    {
        $feedbackModel = new FeedbackAgentForSolicitorOffice;
        return $feedbackModel->getAverage();
    }

    private function getCustomerFeedbackCount()
    {
        $feedbackModel = new FeedbackCustomerForTcp;
        return $feedbackModel->getFeedbackCount();
    }

    private function getAgentFeedbackCount()
    {
        $feedbackModel = new FeedbackAgentForSolicitorOffice;
        return $agentFeedbackCount = $feedbackModel->getFeedbackCount();
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
}
