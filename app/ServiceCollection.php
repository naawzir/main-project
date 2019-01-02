<?php

namespace App;

use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class ServiceCollection extends Model implements AuditableContract
{

    public $timestamps = false;

    use Auditable;
    // This will be expanded as we add more services to the system

    public function transactionServiceCollection()
    {
        return $this->belongsTo('App\TransactionServiceCollection', 'id', 'service_collection_id');
    }
}
