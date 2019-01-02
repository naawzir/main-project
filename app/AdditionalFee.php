<?php

namespace App;

use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class AdditionalFee extends Model implements AuditableContract
{
    use Auditable;

    public function solicitorOffice()
    {
        return $this->belongsTo(SolicitorOffice::class);
    }
}
