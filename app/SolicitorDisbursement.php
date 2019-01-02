<?php

namespace App;

use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class SolicitorDisbursement extends Model implements AuditableContract
{
    use Auditable;

    protected $table = 'solicitor_disbursements';
    const UPDATED_AT = null;
    const CREATED_AT = null;

    /**
     * Get all Disbursements with the  current system
     *
     * @return mixed
     */
    public function getSolicitorDisbursements()
    {
        $sDisbursements = self::get();
        return $sDisbursements;
    }
}
