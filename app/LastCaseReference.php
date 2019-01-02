<?php

namespace App;

use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class LastCaseReference extends Model implements AuditableContract
{
    use Auditable;

    protected $table = 'last_case_reference';
}
