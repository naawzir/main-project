<?php

namespace App;

use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Query\JoinClause;

class TargetsInternalStaff extends Model implements AuditableContract
{
    use Auditable;

    protected $table = "targets_internal_staff";

    public function createTarget($userId, $month, $target)
    {
        $this->user_id = $userId;
        $this->date_from = $month;
        $this->target = $target;
        if ($this->save()) {
            return $this;
        }
        return false;
    }

    public function updateKpiTarget($userId, $target)
    {
        $targetsInternalStaff =
            $this
                ->where([
                    ['user_id', '=', $userId],
                    ['date_from', '=', strtotime('midnight first day of this month')]
                ])
                ->first();

        $targetsInternalStaff->target = $target;
        if ($targetsInternalStaff->save()) {
            return true;
        }

        return false;
    }
}
