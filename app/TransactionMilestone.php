<?php

namespace App;

use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class TransactionMilestone extends Model implements AuditableContract
{
    use Auditable;

    // This model gets all milestones assigned to a Transaction

    public function milestones()
    {

        return $this->belongsTo(Transaction::class);
    }
}
