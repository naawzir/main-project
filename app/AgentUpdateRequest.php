<?php

namespace App;

use Illuminate\Database\Query\JoinClause;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use Illuminate\Support\Facades\DB;

class AgentUpdateRequest extends Model implements AuditableContract
{
    use Auditable;

    public function conveyancingCase()
    {
        return $this->belongsTo('App\Cases', 'case_id');
    }

    public function agentUpdateRequests()
    {
        $requests = self::where('status', 1)
            ->get();

        return $requests;
    }

    public function getAgentUpdateRequestCount()
    {
        $count = $this->where([
            ['case_id', '!=', 'completed'],
            ['date_created', '>=', strtotime('midnight first day of this month')],
        ])->count();

        return $count;
    }
}
