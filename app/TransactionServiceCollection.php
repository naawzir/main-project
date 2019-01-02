<?php

namespace App;

use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class TransactionServiceCollection extends Model implements AuditableContract
{
    public $timestamps = false;

    use Auditable;

    public function transaction()
    {
        return $this->hasOne('App\Transaction', 'id', 'transaction_id');
    }
}
