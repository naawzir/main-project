<?php

namespace App;

use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class PointAward extends Model implements AuditableContract
{
    use Auditable;

    /*public function havePointsBeenAwarded($caseId)
    {
        $pointAwards =
            $this
                ->where('case_id', $caseId)
                ->first();

        if (!empty($pointAwards)) {
            return true;
        }

        return false;
    }*/

/*    public function awardPoints($caseId)
    {
        $case = ConveyancingCase::find($caseId);


        self::create([
            'case_id' => $caseId,
            'user_id_agent' => $case->user_id_agent, // this value will come from the transactions table
            'points' => 20,
            'date_expiry' => strtotime('+18 months 23:59:59'),
        ]);

        return true;
    }*/

    /*public function confirmPointsAwarded($caseId): bool
    {
        if (!$this->havePointsBeenAwarded($caseId)) {
            return $this->awardPoints($caseId);
        }
        return true;
    }*/
}
