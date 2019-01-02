<?php

namespace App;

use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use Illuminate\Support\Facades\DB;

class FeedbackCustomerForTcp extends Model implements AuditableContract
{
    use Auditable;

    protected $table = 'feedback_customers_for_tcp';

    public function getAverage()
    {
        $count = $this->where([
            ['score', '!=', ''],
            ['date_created', '>=', strtotime('midnight first day of this month')],
            ['date_completed', '!=', ''],
        ])->avg('score');

        return $count;
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
