<?php

namespace App;

use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class TransactionCustomer extends Model implements AuditableContract
{
    use Auditable;
}
