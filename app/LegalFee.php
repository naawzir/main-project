<?php

namespace App;

use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use Webpatser\Uuid\Uuid;

class LegalFee extends Model implements AuditableContract
{
    use Auditable;

    public function solicitorOffice()
    {
        return $this->belongsTo(SolicitorOffice::class);
    }
}
