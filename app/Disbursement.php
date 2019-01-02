<?php

namespace App;

use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Disbursement extends Model implements AuditableContract
{
    use Auditable;

    protected $table = 'third_party_disbursements';

    /**
     * Get all Disbursements with the  current system
     *
     * @return mixed
     */
    public function getDisbursements()
    {
        $disbursements = self::where([['active', 1]])->get();
        return $disbursements;
    }

    public static function getDisbursementsFromArray($array)
    {
        $disbursements = self::whereIn('id', $array)->get();
        return $disbursements;
    }
}
