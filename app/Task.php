<?php

namespace App;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Task extends Model implements AuditableContract
{
    use Auditable;

    public function solicitorOffice()
    {
        return $this->belongsTo('App\SolicitorOffice', 'target_id');
    }

    public function getTasksForUser($type)
    {
        switch ($type) {
            case 'alert':
                return self::getAlertTasksForUser();
                break;
            case 'bdm':
                return self::getBdmTasksForUser();
                break;
            case 'branch':
                return self::getBranchTasksForUser();
                break;
            case 'case':
                return self::getCaseTasksForUser();
                break;
            case 'reminder':
                return self::getReminderTasksForUser();
                break;
        }

        return 'Task type: ' . $type . ' not found.';
    }

    private function getAlertTasksForUser()
    {
        return self::select([
            'id',
            'slug',
            DB::raw('DATE_FORMAT(FROM_UNIXTIME(date_due), \'%d/%m/%Y\') as due'),
            'date_due',
            'title as type',
            'notes'
        ])->where([
            ['type', '=', 'alert'],
            ['follow_up', '=', 1],
            ['assigned_to', '=', Auth::user()->id],
            ['date_due', '<=', time()],
        ])->get();
    }

    private function getBdmTasksForUser()
    {
        $bdmTasks =
            self::select([
                'id',
                'slug',
                DB::raw('DATE_FORMAT(FROM_UNIXTIME(date_due), \'%d/%m/%Y\') as due'),
                'date_due',
                'title as type',
                'notes'
            ])->where([
                ['follow_up', '=', 1],
                ['type', '=', 'complete-onboarding'],
                ['assigned_to', '=', Auth::user()->id],
                ['date_due', '<=', time()],
            ]);

        return $bdmTasks;
    }

    private function getBranchTasksForUser()
    {
        $branchTasks =
            self::select([
                'tasks.id as id',
                'slug',
                DB::raw('DATE_FORMAT(FROM_UNIXTIME(date_due), \'%d/%m/%Y\') as due'),
                'date_due',
                'ab.name as branch',
                'tasks.notes'
            ])->where([
                ['follow_up', '=', 1],
                ['target_type', '=', 'branch'],
                ['assigned_to', '=', Auth::user()->id],
                ['date_due', '<=', time()],
            ])->leftJoin('agency_branches as ab', 'ab.id', '=', 'tasks.target_id')->get();

        return $branchTasks;
    }

    private function getCaseTasksForUser()
    {
        $casetasks = self::select([
            'id',
            'slug',
            DB::raw('DATE_FORMAT(FROM_UNIXTIME(date_due), \'%d/%m/%Y\') as due'),
            'date_due',
            'title as type',
            'notes'
        ])->where([
            ['follow_up', '=', 1],
            ['target_type', '=', 'case'],
            ['assigned_to', '=', Auth::user()->id],
            ['date_due', '<=', time()],
        ])->get();
        return $casetasks;
    }

    private function getReminderTasksForUser()
    {
        $remindertasks = self::select([
            'id',
            'slug',
            DB::raw('DATE_FORMAT(FROM_UNIXTIME(date_due), \'%d/%m/%Y\') as due'),
            'date_due',
            'title as type',
            'notes'
        ])->whereIn('type', [
            'solicitor-office-contact',
            'complaint-follow-up',
            'new-solicitor-office',
            'chase-tm-set-up',
            'panel-manager-audit'
        ])->where([
            ['follow_up', '=', 1],
            ['assigned_to', '=', Auth::user()->id],
            ['date_due', '<=', time()],
        ])->get();
        return $remindertasks;
    }

    public function createTask($slug, $title, $target_id, $target_type, $type, $notes, $date_due, $assigned_to)
    {
        $this->slug = $slug;
        $this->title = $title;
        $this->target_id = $target_id;
        $this->target_type = $target_type;
        $this->type = $type;
        $this->notes = $notes;
        $this->follow_up = 1;
        $this->date_due = $date_due > 0 ? $date_due : time();
        $this->assigned_to = $assigned_to;
        if ($this->save()) {
            return true;
        }

        return false;
    }

    public function clearTask($target_id, $type)
    {
        $task = Task::where([['target_id', '=', $target_id], ['type', '=', $type]])->first();
        $user = Auth::user();
        $complete_date = time();
        $task->follow_up = 2;
        $task->date_completed = $complete_date;
        $task->user_id_staff_completed = $user->id;
        $task->task_notes = 'Task complete as of ' . formatDate($complete_date);
        if ($task->save()) {
            return true;
        }

        return false;
    }

    public function generateTaskUrl($task_type)
    {
        $case = $task_type == 'case' ? loadModel(new ConveyancingCase, $this->target_id) : new ConveyancingCase;
        $office = $task_type == 'solicitor-office' ?
            loadModel(new SolicitorOffice, $this->target_id) : new SolicitorOffice;
        $types = [
            'solicitor-office' => '/solicitors/office/' . $office->slug,
            'case' => '/cases?clear=true&reference=' . $case->reference,
            'branch' => '/branch/' . $this->target_id,
        ];

        if (array_key_exists($task_type, $types)) {
            return $types[$task_type];
        }

        return false;
    }


    public function countTasks($type)
    {
        switch ($type) {
            case 'alert':
                return self::getAlertTasksForUser()->count();
                break;
            case 'bdm':
                return self::getBdmTasksForUser()->count();
                break;
            case 'branch':
                return self::getBranchTasksForUser()->count();
                break;
            case 'case':
                return self::getCaseTasksForUser()->count();
                break;
            case 'reminder':
                return self::getReminderTasksForUser()->count();
                break;
        }

        return 0;
    }
}
