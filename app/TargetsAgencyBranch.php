<?php

namespace App;

use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Query\JoinClause;

class TargetsAgencyBranch extends Model implements AuditableContract
{
    use Auditable;

    public function createTarget($agency_id, $branch_id, $month, $target)
    {
        $this->agency_id = $agency_id;
        $this->agency_branch_id = $branch_id;
        $this->date_from = $month;
        $this->target = $target;
        if ($this->save()) {
            return $this;
        }
        return false;
    }

    public function updateKpiTarget($branchId, $target)
    {
        $agencyBranchesTarget =
            $this
                ->where([
                    ['agency_branch_id', '=', $branchId]
                ])
                ->where('date_from', strtotime('midnight first day of this month'))
                ->first();

        $agencyBranchesTarget->target = $target;
        if ($agencyBranchesTarget->save()) {
            return true;
        }

        return false;
    }
}
