<?php

namespace App;

use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class AgentUser extends Model implements AuditableContract
{
    use Auditable;

    public $timestamps = false;

    protected $table = "user_agents";
    
    // An Agent User belongs to a User
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    // An Agent User belongs to an Agency
    public function agency()
    {
        return $this->belongsTo('App\Agency');
    }

    // An Agent User belongs to an Branch if we get this information via API
    public function agencyBranch()
    {
        return $this->belongsTo('App\AgencyBranch');
    }

    // An Agent User has many Branches via TCP
    // public function agencyBranch()
    // {
    //     return $this->hasMany('App\AgencyBranch');
    // }

    public function transaction()
    {
        return $this->hasMany('App\Transaction');
    }
}
