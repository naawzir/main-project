<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use Illuminate\Support\Facades\DB;

/**
 * @method static Builder|self onboarding(bool $bdm = false)
 */
class Solicitor extends Model implements AuditableContract
{
    use Auditable, GeneratesSlug;

    public function defaultOffice()
    {
        return $this->hasOne(SolicitorOffice::class, 'id', 'default_office');
    }

    public function offices()
    {
        return $this->hasMany(SolicitorOffice::class);
    }

    /**
     * Finds solicitors that have offices that are onboarding
     *
     * @param Builder $query
     * @param bool $bdm
     */
    public function scopeOnboarding(Builder $query, bool $bdm = false)
    {
        $query->whereHas('offices', function ($q) use ($bdm) {
            $q->onboarding($bdm);
        });
    }

    public function activeSolicitors()
    {
        $solicitors =
            self::where('status', 'Active')
                ->orderBy('name')
                ->get();

        return $solicitors;
    }

    public function getSolicitorMarketDetails()
    {
        $records =
            DB::select(
                DB::raw(
                    "SELECT 
                                so.slug AS OfficeId, 
                                s.slug AS SolicitorID, 
                                s.name AS Solicitor, 
                                ROUND(COALESCE(AVG(DATEDIFF(FROM_UNIXTIME(MS2.MilestoneDate, '%Y-%m-%d'),
                                    FROM_UNIXTIME(MS1.MilestoneDate, '%Y-%m-%d'))), 0), 0) AS AverageCompletion, 
                                    so.Location AS Location, 
                                    a.postcode AS Postcode, 
                                    IF(ar.AgentRating IS NOT NULL, ar.AgentRating, 0) AS AgentRating,
                                fs.legalfee AS LegalFee
                                FROM `solicitors` AS `s` 
                                LEFT JOIN (
                                    SELECT 
                                        id, 
                                        slug, 
                                        solicitor_id, 
                                        address_id, 
                                        status, 
                                        office_name AS Location 
                                    FROM solicitor_offices) AS so 
                                ON `so`.`solicitor_id` = `s`.`id`
                                INNER JOIN (
                                    SELECT 
                                        solicitor_office_id, 
                                        MIN(legal_fee) AS legalfee
                                FROM legal_fees 
                                GROUP BY solicitor_office_id) AS fs 
                                ON `fs`.`solicitor_office_id` = `so`.`id`
                                LEFT JOIN (
                                    SELECT 
                                        solicitor_office_id, 
                                        id 
                                    FROM conveyancing_cases) AS c ON `c`.`solicitor_office_id` = `so`.`id` 
                                LEFT JOIN (
                                    SELECT 
                                        id, 
                                        address_line_1, 
                                        address_line_2, 
                                        town, 
                                        postcode 
                                    FROM addresses) AS a ON `a`.`id` = `so`.`address_id` 
                                LEFT JOIN (
                                    SELECT 
                                        solicitor_office_id, 
                                        ROUND(AVG(score)/2,0) AS AgentRating
                                    FROM feedback_agents_for_solicitor_offices 
                                    WHERE score != 'na' 
                                    GROUP BY solicitor_office_id) AS ar ON `ar`.`solicitor_office_id` = `so`.`id` 
                                LEFT JOIN (
                                    SELECT 
                                        id, 
                                        date_created AS MilestoneDate 
                                    FROM conveyancing_cases) AS MS1 ON `MS1`.`id` = `c`.`id` 
                                LEFT JOIN (
                                    SELECT 
                                        id, 
                                        date_exchanged AS MilestoneDate 
                                    FROM conveyancing_cases) AS MS2 ON `MS2`.`id` = `c`.`id` 
                                WHERE (`s`.`status` = 'Active' AND `so`.`status` = 'Active') 
                                GROUP BY `so`.`id` 
                                ORDER BY `s`.`name` ASC"
                )
            );

        return $records;
    }

    public function hasEmail()
    {
        return $this->email ? true : false;
    }

    public function create($request, $solicitorOffice = false)
    {
        $this->name = $request->name;
        $this->email = $request->email;
        $this->contract_signed = $request->contract_signed;
        //$this->default_office = $solicitorOffice->id;
        if ($this->save()) {
            return $this;
        }

        return false;
    }

    public function currentPerformanceKPIs($date)
    {
        $statuses = [
            'completed',
            'aborted',
            'instructed',
            'instructed_unpanelled'
        ];

        $count = [];

        foreach ($statuses as $status) {
            $queryCount =
                ConveyancingCase::where([
                    ['active', '=', 1],
                    ['status', '=', $status],
                    ['date_created', '>=', $date],
                    ['solicitor_id', '!=', '']
                ])->count();

            $count[$status] = $queryCount;
        }

        return $count;
    }

    public function getSolicitorsStatsRecords()
    {
        $date = strtotime('midnight first day of this month');
        $records = DB::select(
            DB::raw(
                "
                SELECT
                    s.id,
                    office_count.total as `office_count`,
                    s.slug,
                    #s.name as `solicitor`,
                    concat(s.name, ' (',  office_count.total, ')') as `solicitor`,
                    capacity.total as `capacity`,
                    COALESCE(pipeline.total, 0) AS `pipeline`, #number of cases currently instructed
                    COALESCE(capacity.total, 0) - COALESCE(pipeline.total, 0) AS `capacity_remaining`,
                    COALESCE(instructions_mtd.total, 0) AS `instructions_mtd`,
                    COALESCE(unpanelled_instructions_mtd.total, 0) AS `unpanelled_instructions_mtd`,
                    COALESCE(completions_mtd.total, 0) AS `completions_mtd`,
                    COALESCE(aborts_mtd.total, 0) AS `aborts_mtd`
                FROM conveyancing_cases AS `c`
                INNER JOIN solicitor_offices AS `so` ON so.id = c.solicitor_office_id
                INNER JOIN solicitors AS `s` ON s.id = so.solicitor_id
                LEFT JOIN (
                   select
                        so.id, so.solicitor_id, count(solicitor_id) as total
                     from solicitor_offices as `so`
                     where so.status = 'Active'
                     group by so.solicitor_id
                ) AS office_count ON office_count.solicitor_id = c.solicitor_id
                LEFT JOIN (
                    # CAPACITY
                    select 
                        solicitor_id,
                        sum(coalesce(capacity, 0)) as `total` from solicitor_offices as `so`
                    where status = 'Active'
                    group by so.solicitor_id
                ) AS capacity ON capacity.solicitor_id = so.solicitor_id
                LEFT JOIN (
                    # INSTRUCTIONS
                    SELECT 
                        c.solicitor_id AS solicitor_id, COUNT(id) AS total
                    FROM conveyancing_cases AS `c`
                    WHERE c.active = 1
                    AND c.status = 'instructed'
                    GROUP BY c.solicitor_id
                ) AS pipeline ON pipeline.solicitor_id = so.solicitor_id  
                LEFT JOIN (
                    # INSTRUCTIONS MONTH TO DATE
                    SELECT 
                        c.solicitor_id AS solicitor_id, COUNT(id) AS total
                    FROM conveyancing_cases AS `c`
                    WHERE c.active = 1
                    AND c.status = 'instructed'
                    AND c.date_created >= $date
                    GROUP BY c.solicitor_id
                ) AS instructions_mtd ON instructions_mtd.solicitor_id = so.solicitor_id
                LEFT JOIN (
                    # INSTRUCTED UNPANELLED MONTH TO DATE
                    SELECT 
                        c.solicitor_id AS solicitor_id, COUNT(id) AS total
                    FROM conveyancing_cases AS `c`
                    WHERE c.active = 1
                    AND c.status = 'instructed_unpanelled'
                    AND c.date_created > $date
                    GROUP BY c.solicitor_id
                ) AS unpanelled_instructions_mtd ON unpanelled_instructions_mtd.solicitor_id = so.solicitor_id
                LEFT JOIN (
                    # COMPLETIONS MONTH TO DATE
                    SELECT 	
                        c.solicitor_id AS solicitor_id, COUNT(id) AS total
                    FROM conveyancing_cases AS `c`
                    WHERE c.active = 1
                    AND c.status = 'completed'
                    AND c.date_created > $date
                    GROUP BY c.solicitor_id
                ) AS completions_mtd ON completions_mtd.solicitor_id = so.solicitor_id
                LEFT JOIN (
                    # ABORTED
                    SELECT 	
                        c.solicitor_id AS solicitor_id, COUNT(id) AS total
                    FROM conveyancing_cases AS `c`
                    WHERE c.active = 1
                    AND c.status = 'aborted'
                    AND c.date_created > $date
                    GROUP BY c.solicitor_id
                ) AS aborts_mtd ON aborts_mtd.solicitor_id = so.solicitor_id   
                GROUP BY c.solicitor_office_id"
            )
        );

        return $records;
    }

    public function getSolicitorStatsRecords($solicitorId)
    {
        $date = strtotime('midnight first day of this month');
        $records =
            DB::select(
                DB::raw(
                    "
                SELECT
                    s.id,
                    s.name as `solicitor`,
                    so.id,
                    so.slug,
                    so.office_name as `solicitorOffice`,
                    so.capacity,
                    COALESCE(pipeline.total, 0) AS `pipeline`, #number of cases currently instructed
                    COALESCE(so.capacity, 0) - COALESCE(pipeline.total, 0) AS `capacity_remaining`,
                    COALESCE(instructions_mtd.total, 0) AS `instructions_mtd`,
                    COALESCE(unpanelled_instructions_mtd.total, 0) AS `unpanelled_instructions_mtd`,
                    COALESCE(completions_mtd.total, 0) AS `completions_mtd`,
                    COALESCE(aborts_mtd.total, 0) AS `aborts_mtd`
                FROM conveyancing_cases AS `c`
                #LEFT JOIN solicitors AS `s` ON s.id = c.solicitor_id
                #LEFT JOIN solicitor_offices AS `so` ON so.solicitor_id = s.id
                INNER JOIN solicitor_offices AS `so` ON so.id = c.solicitor_office_id
                INNER JOIN solicitors AS `s` ON s.id = so.solicitor_id
                LEFT JOIN (
                    # INSTRUCTIONS
                    SELECT 
                       c.solicitor_office_id AS solicitor_office_id, 
                       COUNT(id) AS total
                    FROM conveyancing_cases AS `c`
                    WHERE c.active = 1
                    AND c.status = 'instructed'
                    GROUP BY c.solicitor_office_id
                ) AS pipeline ON pipeline.solicitor_office_id = so.id  
                LEFT JOIN (
                    # INSTRUCTIONS MONTH TO DATE
                    SELECT 
                       c.solicitor_office_id AS solicitor_office_id, 
                       COUNT(id) AS total
                    FROM conveyancing_cases AS `c`
                    WHERE c.active = 1
                    AND c.status = 'instructed'
                    AND c.date_created > $date
                    GROUP BY c.solicitor_office_id
                ) AS instructions_mtd ON instructions_mtd.solicitor_office_id = so.id
                LEFT JOIN (
                    # INSTRUCTED UNPANELLED MONTH TO DATE
                    SELECT 
                       c.solicitor_office_id AS solicitor_office_id, 
                       COUNT(id) AS total
                    FROM conveyancing_cases AS `c`
                    WHERE c.active = 1
                    AND c.status = 'instructed_unpanelled'
                    AND c.date_created > $date
                    GROUP BY c.solicitor_office_id
                ) AS unpanelled_instructions_mtd ON unpanelled_instructions_mtd.solicitor_office_id = so.id
                LEFT JOIN (
                    # COMPLETIONS MONTH TO DATE
                    SELECT 	
                       c.solicitor_office_id AS solicitor_office_id, 
                       COUNT(id) AS total
                    FROM conveyancing_cases AS `c`
                    WHERE c.active = 1
                    AND c.status = 'completed'
                    AND c.date_created > $date
                    GROUP BY c.solicitor_id
                ) AS completions_mtd ON completions_mtd.solicitor_office_id = so.id
                LEFT JOIN (
                    # ABORTED
                    SELECT 	
                       c.solicitor_office_id AS solicitor_office_id, 
                       COUNT(id) AS total
                    FROM conveyancing_cases AS `c`
                    WHERE c.active = 1
                    AND c.status = 'aborted'
                    AND c.date_created > $date
                    GROUP BY c.solicitor_id
                ) AS aborts_mtd ON aborts_mtd.solicitor_office_id = so.id
                 WHERE s.id = $solicitorId
                 AND so.`status` = 'Active'
                 group by so.id"
                )
            );

        return $records;
    }

    public function getMySolicitors()
    {
        $user = Auth::User();
        $agency = null;
        if (!empty($user->agencyUser)) {
            $agencySolicitorPanel = new AgencySolicitorPanel;
            $solicitorOfficeIds = $agencySolicitorPanel->solicitorOfficesAssignedToAgency();
            $solicitorOfficeIds = implode(",", $solicitorOfficeIds);
            $agency = $user->agencyUser->agency->id;
        } elseif (!empty($user->solicitorUser)) { // in future, this will be like a Solicitor Business Owner
            $solicitorUser = $user->solicitorUser;
            $solicitor = $solicitorUser->solicitor;
            $solicitorOffices = $solicitor->offices;
            $solicitorOfficeIds = $solicitorOffices->pluck('id')->toArray();
            $solicitorOfficeIds = implode(",", $solicitorOfficeIds);
        }

        $mySolicitors = null;

        if (!empty($solicitorOfficeIds)) {
            $mySolicitors = DB::select(
                DB::raw("select 
                    s.id, so.slug, s.name AS Solicitor, so.office_name AS OfficeName,
                    COALESCE(pipeline.total, 0) AS `pipeline`, #number of cases currently instructed
                    ROUND(COALESCE(AVG(DATEDIFF(FROM_UNIXTIME(MS2.MilestoneDate, '%Y-%m-%d'),
                    FROM_UNIXTIME(MS1.MilestoneDate, '%Y-%m-%d'))), 0), 0) AS AverageCompletion,
                    IF(ar.AgentRating IS NOT NULL, ar.AgentRating, 0) AS AgentRating, 
                    IF(cr.CustomerRating IS NOT NULL, cr.CustomerRating, 0) AS CustomerRating,
                    legal_fee
            from `solicitors` as `s`
            left join (
               SELECT id, slug, office_name, solicitor_id, address_id, status, office_name AS Location 
               FROM solicitor_offices) AS so 
            on `so`.`solicitor_id` = `s`.`id`
            LEFT JOIN (
            SELECT 
                c.solicitor_id AS solicitor_id, 
                COUNT(c.id) AS total
            FROM conveyancing_cases AS `c`
            INNER JOIN `service_collections` as `sc` ON `sc`.`target_id` = `c`.`id`
	        INNER JOIN `transaction_service_collections` as `tsc` ON `tsc`.`service_collection_id` = `sc`.`id`
            INNER JOIN `transactions` as `t` ON `t`.`id` = `tsc`.`transaction_id`
            WHERE c.active = 1
            AND c.status = 'instructed'
            AND t.agency_id = $agency
            GROUP BY c.solicitor_id
            ) AS pipeline ON pipeline.solicitor_id = so.solicitor_id  
            left join (
               SELECT 
                  solicitor_office_id, 
                  id 
               FROM conveyancing_cases) AS c on `c`.`solicitor_office_id` = `so`.`id` 
            left join (
               SELECT id, address_line_1, address_line_2, town, postcode 
            FROM addresses) AS a on `a`.`id` = `so`.`address_id`
            left join (
               SELECT 
                   id, 
                   date_created AS MilestoneDate FROM conveyancing_cases) AS MS1 on `MS1`.`id` = `c`.`id` 
            left join (
               SELECT 
                   id, 
                   date_exchanged AS MilestoneDate 
               FROM conveyancing_cases) AS MS2 on `MS2`.`id` = `c`.`id` 
               left join (
            SELECT 
                solicitor_office_id, 
                ROUND(AVG(score),0) AS AgentRating
            FROM feedback_agents_for_solicitor_offices 
            WHERE score != 'na' 
            GROUP BY solicitor_office_id) AS ar on `ar`.`solicitor_office_id` = `so`.`id` 
               left join (
            SELECT 
                solicitor_office_id, 
                ROUND(AVG(score),0) AS CustomerRating
            FROM feedback_customers_for_solicitor_offices 
            WHERE score != 'na' 
            GROUP BY solicitor_office_id) AS cr on `cr`.`solicitor_office_id` = `so`.`id` 
            LEFT JOIN( 
                SELECT
                solicitor_office_id,
                MIN(legal_fee) AS legal_fee
            FROM legal_fees
            GROUP BY solicitor_office_id) AS fs ON `fs`.solicitor_office_id = `so`.`id`
            where 
                  `s`.`status` = 'Active' 
                  AND `so`.`status` = 'Active' 
                  AND so.id IN ({$solicitorOfficeIds})
            group by `so`.`id` 
            order by `s`.`name` asc")
            );
        }

        return $mySolicitors;
    }
}
