<?php

namespace App;

use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Agency extends Model implements AuditableContract
{
    use Auditable;
    // An Agency can have many Branches
    public function branches()
    {
        return $this->hasMany('App\AgencyBranch');
    }

    // An Agency can have many Transactions
    public function transaction()
    {
        return $this->hasMany('App\Transaction');
    }

    public function staff()
    {
        return $this->hasManyThrough(User::class, AgentUser::class, 'agency_id', 'id', 'id', 'user_id');
    }

    // An Agency can have many Pricing Structures
/*    public function pricingStructure()
    {
        return $this->hasMany('App\PricingStructure');
    }*/

    public function activeAgencies()
    {
        $agencies =
            $this->where('active', 1)
                ->orderBy('name')
                ->get();

        return $agencies;
    }

    public function getDistinctAgencies()
    {
        $agencies =
            $this
                ->whereNotNull('name')
                ->groupBy('name')
                ->orderBy('name', 'asc')
                ->get([
                    'id',
                    'name'
                ]);

        return $agencies;
    }
}
