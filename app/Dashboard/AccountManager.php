<?php declare(strict_types=1);

namespace App\Dashboard;

use App\User;
use App\Transaction;
use App\Task;
use App\TargetsInternalStaff;

class AccountManager implements Dashboard
{
    public function getData(User $user): array
    {
        $data = [
            'target' => $this->getMonthlyTarget($user),
            'kpis' => $this->getKpis($user),
            'currentActivity' => $this->getCurrentActivity($user),
            'predictedFinishRisk' => $this->predictedFinishRisk($user),
            'predictedFinish' => $this->predictedFinish($user),
            'caseTasksCount' => $this->getCaseTasksCount(),
            'branchTasksCount' => $this->getBranchTasksCount(),
        ];

        return $data;
    }

    private function getCaseTasksCount()
    {
        $taskModel = new Task;
        return $reminderTasks = $taskModel->countTasks('case');
    }

    private function getBranchTasksCount()
    {
        $taskModel = new Task;
        return $alertTasks = $taskModel->countTasks('branch');
    }

    private function getMonthlyTarget(User $user)
    {
        $targetsInternalStaff = new TargetsInternalStaff;
        return $targetsInternalStaff->where([
            ['user_id', '=', $user->id],
            ['date_from', '=', strtotime('midnight first day of this month')],
        ])->first();
    }

    private function predictedFinishRisk(User $user, $from = null)
    {
        if ($from === null) {
            $from = strtotime('midnight first day of this month');
        }
        $predictedFinish = $this->predictedFinish($user, $from);
        $riskOriginal = $predictedFinish - ($this->getMonthlyTarget($user)->target ?? 0);
        $riskCeiled = ceil($riskOriginal);
        return $riskCeiled;
        //$risk = $riskCeiled == '-0' ? 0 : $riskCeiled;
    }

    private function predictedFinish(User $user, $from = null)
    {
        if ($from === null) {
            $from = strtotime('midnight first day of this month');
        }

        $mtdInstructions = $this->getInstructionsData($user, $from);
        $numberOfDays = workingDaysInMonth($includeBankHolidays = true);
        $currentWD = currentWorkingDayInCurrentMonth($includeBankHolidays = true);
        $actualDailyRunRate = $mtdInstructions / $currentWD;
        $predictedFinish = $actualDailyRunRate * $numberOfDays;
        return ceil($predictedFinish);
    }

    private function getKpis(User $user, $from = null)
    {
        if ($from === null) {
            $from = strtotime('midnight first day of this month');
        }

        $today = strtotime('today GMT');

        $mtdTarget = !empty($this->getMonthlyTarget($user)->target) ? $this->getMonthlyTarget($user)->target : 0;
        $mtdInstructions = $this->getInstructionsData($user, $from);
        $mtdInstructionsTotal = $mtdTarget - $mtdInstructions;

        $numberOfDays = workingDaysInMonth($includeBankHolidays = true);
        $dailyTarget = $mtdTarget / $numberOfDays;
        $dailyTargetRounded = number_format((float)$dailyTarget, 2, '.', '');

        $currentWD = currentWorkingDayInCurrentMonth($includeBankHolidays = true);
        $actualDailyRunRate = $mtdInstructions / $currentWD;
        $actualDRR = number_format((float)$actualDailyRunRate, 2, '.', '');
        $dailyInstructionTot = ceil($dailyTargetRounded) - ceil($actualDRR);

        return [
            'mtdTarget' => [
                [
                    'amount' => $mtdTarget,
                    'color' => '#CFD7AE',
                    'desc' => 'Target'
                ],
                [
                    'amount' => 0,
                    'color' => '#d8d6d7'
                ]
            ],
            'mtdInstructions' => [
                [
                    'amount' => $mtdInstructions,
                    'color' => '#E3BB4B',
                    'desc' => 'Instructions MTD'
                ],
                [
                    'amount' => $mtdInstructionsTotal,
                    'color' => '#d8d6d7'
                ]
            ],
            'dailyTarget' => [
                [
                    'amount' => ceil($dailyTargetRounded),
                    'color' => '#CFD7AE',
                    'desc' => 'Target'
                ],
                [
                    'amount' => 0,
                    'color' => '#d8d6d7'
                ]
            ],
            'dailyInstructions' => [
                [
                    'amount' => ceil($actualDRR),
                    'color' => '#E3BB4B',
                    'desc' => 'Actual'
                ],
                [
                    'amount' => $dailyInstructionTot,
                    'color' => '#d8d6d7'
                ]
            ]
        ];
    }

    private function getInstructionsData($user, $date)
    {
        return Transaction::where([
            ['active', '=', 1],
            ['date_created', '>=', $date],
            ['status', '=', 'instructed'],
            ['staff_user_id', '=', $user->id]
        ])->count();
    }

    public function getCurrentActivity($user, $from = null)
    {
        if ($from === null) {
            $from = strtotime('midnight first day of this month');
        }

        $statuses = [
            'prospect',
            'completed',
            'aborted',
            'instructed_unpanelled'
        ];

        $count = [];

        foreach ($statuses as $status) {
            $queryCount =
                Transaction::where([
                    ['active', '=', 1],
                    ['status', '=', $status],
                    ['date_created', '>=', $from],
                    ['staff_user_id', '=', $user->id]
                ])->count();

            $count[$status] = $queryCount;
        }

        return $count;
    }
}
