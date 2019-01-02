<?php

namespace App;

use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class FeedbackAgentForSolicitorOffice extends Model implements AuditableContract
{
    use Auditable;

    protected $table = 'feedback_agents_for_solicitor_offices';

    const UPDATED_AT = null;

    public function getAverage()
    {
        $average = $this->where([
            ['score', '!=', ''],
            ['date_created', '>=', strtotime('midnight first day of this month')],
        ])->avg('score');

        return $average;
    }

    public function getAverageForSolicitorOffice($solicitorOfficeId)
    {
        $average = $this->where([
            ['score', '!=', ''],
            ['date_created', '>=', strtotime('midnight first day of this month')],
            ['solicitor_office_id', '=', $solicitorOfficeId]
        ])->avg('score');

        return $average;
    }

    public function getFeedbackCount()
    {
        $count = $this->where([
            ['score', '!=', ''],
            ['date_created', '>=', strtotime('midnight first day of this month')],
        ])->count();

        return $count;
    }
}
