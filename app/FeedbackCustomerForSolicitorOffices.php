<?php

namespace App;

use Auth;
use App\User;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use Illuminate\Support\Facades\DB;

class FeedbackCustomerForSolicitorOffices extends Model implements AuditableContract
{
    use Auditable;

    protected $table = 'feedback_customers_for_solicitor_offices';

    private function getSolicitorOffices()
    {
        $agencySolPanel = new AgencySolicitorPanel;
        return $agencySolPanel->solicitorOfficesAssignedToAgency();
    }

    // displayed on the Business Owner dashboard - NOT the Service Feedback page
    public function getAverage()
    {
        $count = $this->where([
            ['score', '!=', ''],
            ['date_created', '>=', strtotime('midnight first day of this month')],
            ['date_completed', '!=', ''],
        ])
        ->whereIn('solicitor_office_id', $this->getSolicitorOffices())
        ->avg('score');

        return $count;
    }
    
    // displayed on the Business Owner dashboard - NOT the Service Feedback page
    public function getFeedbackCount()
    {
        $count = $this->where([
            ['score', '!=', ''],
            ['date_created', '>=', strtotime('midnight first day of this month')],
        ])
        ->whereIn('solicitor_office_id', $this->getSolicitorOffices())
        ->count();

        return $count;
    }

    // displayed on the Service Feedback page
    public function getHighScoresCount()
    {
        $count = $this->where([
            ['date_created', '>=', strtotime('midnight first day of this month')],
            ['score', '>', 5],
            ['score', '!=', '']
        ])
        ->whereIn('solicitor_office_id', $this->getSolicitorOffices())
        ->count();

        return $count;
    }

    // displayed on the Service Feedback page
    public function getLowScoresCount()
    {
        $count = $this->where([
            ['date_created', '>=', strtotime('midnight first day of this month')],
            ['score', '<', 6],
            ['score', '!=', '']
        ])
        ->whereIn('solicitor_office_id', $this->getSolicitorOffices())
        ->count();

        return $count;
    }

    // displayed on the Service Feedback page
    public function getAverageDonut()
    {
        $count = $this->where([
            ['score', '!=', ''],
            ['date_created', '>=', strtotime('midnight first day of this month')],
            ['date_completed', '!=', ''],
        ])
        ->whereIn('solicitor_office_id', $this->getSolicitorOffices())
        ->avg('score');

        $total = 10;
        $completed = !empty($total) ? 10 / $total * $count : 0;
        $completed = round($completed, 1);
        $notCompleted = 10 - $completed;

        return [
            'AverageScoreCustomer' => [
                [
                    'amount' => $completed,
                    'color' => '#B7C582',
                    'desc' => 'Average Score'
                ],
                [
                    'amount' => $notCompleted,
                    'color' => '#d8d6d7'
                ]
            ]
        ];
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

    // displayed on the Service Feedback page
    public function getCustomerFeedbackResponseRate()
    {
        $nonCompletedFeedback = $this->where([
            ['date_completed', '=', null],
            ['date_created', '>=', strtotime('midnight first day of this month')],
        ])
            ->whereIn('solicitor_office_id', $this->getSolicitorOffices())
            ->count();

        $completedFeedback = $this->where([
            ['date_completed', '!=', null],
            ['date_created', '>=', strtotime('midnight first day of this month')],
        ])
        ->whereIn('solicitor_office_id', $this->getSolicitorOffices())
        ->count();

        $total = $nonCompletedFeedback + $completedFeedback;
        $completed = !empty($total) ? round(100 / $total * $completedFeedback) : 0;
        $notCompleted = 100 - $completed;

        return [
            'responseRateCustomer' => [
                [
                    'amount' => $completed,
                    'color' => '#E3BB4B',
                    'desc' => 'Response Rate'
                ],
                [
                    'amount' => $notCompleted,
                    'color' => '#d8d6d7'
                ]
            ]
        ];
    }

    public function lowScoringCustomerFeedback()
    {
        $date = strtotime('midnight first day of this month');
        $cases = DB::select(
            DB::raw("SELECT
                DATE_FORMAT(FROM_UNIXTIME(f.date_completed), '%d/%m/%Y') AS date_created,
                f.date_completed AS date_created_raw,
                c.id,
                c.slug as `case_slug`,
                c.reference,
                c.type AS `transaction`,
                CONCAT(cu.title, ' ', cu.forename, ' ', cu.surname) AS `Customer`,
                CONCAT(s.name, ' (', so.office_name, ')') AS `Solicitor`,
                so.id,
                f.score
            FROM `feedback_customers_for_solicitor_offices` AS `f`
            INNER JOIN `conveyancing_cases` as `c` ON `c`.`id` = `f`.`case_id`
            INNER JOIN `service_collections` as `sc` ON `sc`.`target_id` = `c`.`id`
            INNER JOIN `transaction_service_collections` as `tsc` ON `tsc`.`service_collection_id` = `sc`.`id`
            INNER JOIN `transactions` as `t` ON `t`.`id` = `tsc`.`transaction_id`
            LEFT JOIN `customers` AS `cu` ON `cu`.`id` = `f`.`customer_id`
            LEFT JOIN `solicitors` AS `s` ON `s`.`id` = `c`.`solicitor_id`
            LEFT JOIN `solicitor_offices` AS `so` ON `so`.`solicitor_id` = `s`.`id`
            WHERE 
                `f`.`date_created` >= $date AND
                `f`.`date_completed` != '' AND
                `c`.`active` = 1 
                AND `sc`.`target_type` = 'conveyancing_case' 
                AND `c`.`reference` != ''
                AND f.score < 6
            GROUP BY `c`.`id`")
        );
        return $cases;
    }
}
