<?php declare(strict_types=1);

namespace App\Dashboard;

use App\User;
use App\Task;

class Bdm implements Dashboard
{
    public function getData(User $user): array
    {
        return [
            'bdmTasksCount' => $this->getBdmTasksCount(),
        ];
    }

    private function getBdmTasksCount()
    {
        $taskModel = new Task;
        return $reminderTasks = $taskModel->countTasks('bdm');
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
