<?php declare(strict_types=1);

namespace App\Dashboard;

use App\AgencyBranch;
use App\User;
use App\Task;
use App\Agency;

class Director implements Dashboard
{
    public function getData(User $user): array
    {
        $data = array(
            'kpis' => $this->kpisAjax($user),
            'bdmTasksCount' => $this->getBdmTasksCount(),
        );


        return $data;
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
    private function kpisAjax($user)
    {
        $dateFrom = !empty($request['date']) ? $request['date'] : strtotime('midnight first day of this month');
        $dateTo = !empty($request['dateTo']) ? $request['dateTo'] : time();

        $kpis = null;
        $agencies = Agency::where('active', 1)->get();

        $branchIds = [];
        foreach ($agencies as $agency) {
            $branches = $agency->branches()->where('active', 1)->get();
            foreach ($branches as $branch) {
                $branchIds[] = $branch->id;
            }
        }


        $branchModel = new AgencyBranch;
        $kpis = $branchModel->branchPerformanceKpiCount($branchIds, $dateFrom, $dateTo);
        return $kpis;
    }
}
