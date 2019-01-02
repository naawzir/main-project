<?php

namespace App;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class ConveyancingCase extends Model implements AuditableContract
{
    use Auditable;

    // A Conveyancing Case can have only one Service Collection
    public function serviceCollection()
    {
        return $this->belongsTo('App\ServiceCollection', 'id', 'target_id');
    }

    // A Conveyancing Case can have only one Solicitor
    public function solicitor()
    {
        return $this->hasOne('App\Solicitor');
    }

    // A Conveyancing Case can have only one Solicitor Office
    public function solicitorOffice()
    {
        return $this->hasOne('App\SolicitorOffice');
    }

    // A Conveyancing Case can have only one Solicitor User
    public function solicitorUser()
    {
        return $this->hasOne('App\SolicitorUser');
    }

    // A Conveyancing Case can have only one Fee Structure
    public function feeStructure()
    {
        return $this->hasOne('App\LegalFee');
    }

    // A Conveyancing Case can have many Documents
    public function document()
    {
        return $this->hasMany('App\Document');
    }

    // A Conveyancing Case can have many Milestones
    public function milestones()
    {
        return $this->hasMany('App\Milestones');
    }

    // A Conveyancing Case can have many Agent Update Requests
    public function agentUpdateRequests()
    {
        return $this->hasMany('App\AgentUpdateRequest', 'case_id', 'id');
    }

    public function transaction()
    {
        $sCollection =
            $this->serviceCollection()
                ->where('target_type', 'conveyancing_case')
                ->first();

        $tsCollection = $sCollection->transactionServiceCollection;

        return $tsCollection->transaction;
    }

    public function getCasesForAgencyUsers(
        $date,
        $user,
        $userAgent,
        $userIdAgent
    ) {
        $userRole = $user->userRole;
        if (!empty($userIdAgent)) {
            $whereClauseColumn = 't.agent_user_id';
            $whereClauseValue = [Auth::user()->id];
        } elseif ($userRole->id === 7) { // Business Owner
            // get branches for this agency
            $branchModel = new AgencyBranch;
            $branches = $branchModel->businessOwnerBranches();
            $branchArray = [];
            foreach ($branches as $branch) {
                $branchArray[] = $branch->id;
            }
            $whereClauseColumn = 't.agency_branch_id';
            $whereClauseValue = $branchArray;
        } elseif ($userRole->group === 'Agent' || $userRole->id === 8) {
            $whereClauseColumn = 't.agency_branch_id';
            $whereClauseValue = [$userAgent->agency_branch_id];
        }

        $cases = DB::table('conveyancing_cases AS c')
            ->join('service_collections as sc', 'sc.target_id', '=', 'c.id')
            ->join('transaction_service_collections as tsc', 'tsc.service_collection_id', '=', 'sc.id')
            ->join('transactions as t', 't.id', '=', 'tsc.transaction_id')
            ->leftJoin('agencies as a', 'a.id', '=', 't.agency_id')
            ->leftJoin('agency_branches as ab', 'ab.id', '=', 't.agency_branch_id')
            ->leftJoin('solicitors as s', 's.id', '=', 'c.solicitor_id')
            ->leftJoin('solicitor_offices as so', 'so.id', '=', 'c.solicitor_office_id')
            ->leftJoin('users as u', 'u.id', '=', 't.agent_user_id')
            ->leftJoin('users as u2', 'u2.id', '=', 't.staff_user_id')
            ->leftJoin('addresses as ad', 'ad.id', '=', 't.address_id')
            ->select([
                DB::raw('u2.id as `account_manager_user_id`'),
                DB::raw("CONCAT(u2.forenames, ' ', u2.surname) as `account_manager_name`"),
                DB::raw('t.agency_branch_id as `branch_id`'),
                DB::raw('a.name as `Agency`'),
                DB::raw('ab.name as `Branch`'),
                DB::raw('ab.name as `Branch`'),
                DB::raw('t.agent_user_id'),
                DB::raw('s.name as `Solicitor`'),
                DB::raw('so.office_name as `Office`'),
                DB::raw('c.type as `transaction`'),
                'c.id', 'c.reference', 'c.active', 'c.status',
                DB::raw("IF(c.status = 'instructed_unpanelled', 'Instructed Unpanelled', c.status) as `status`"),
                DB::raw("DATE_FORMAT(FROM_UNIXTIME(c.date_created), '%d/%m/%Y') AS date_created"),
                DB::raw('c.date_created AS date_created_raw'),
                DB::raw(
                    "CONCAT(
                            u.forenames, ' ',
                            u.surname) 
                    as `Agent Name`"
                ),
                DB::raw(
                    "CONCAT(
                        u2.forenames, ' ', 
                        u2.surname) 
                    as `AM Name`"
                ),
                DB::raw(
                    "CONCAT(
                        ad.building_number, ', ', 
                        ad.address_line_1, ', ', 
                        ad.town, ', ',
                        ad.county, ', ',
                        ad.postcode, ' ') 
                    as `TransactionAddress`"
                )
            ])
            ->orderBy('c.date_created', 'desc')
            ->whereIn($whereClauseColumn, $whereClauseValue)
            ->where([
                ['c.date_created', '>=', $date],
                ['c.active', '=', 1],
                ['sc.target_type', '=', 'conveyancing_case'],
                ['c.reference', '!=', ''],
            ])
            ->groupBy(['c.id'])
            ->get();

        return $cases;
    }

    public function getCasesForAdminUsers($date, $myBranches)
    {
        $user = Auth::user();
        if (!empty($myBranches)) {
            $branches = DB::table('agencies as a')
                ->join('agency_branches as b', 'b.agency_id', '=', 'a.id')
                ->select([
                    DB::raw("a.name as `Agency`"),
                    DB::raw("b.id"),
                    DB::raw("b.name as `Branch`"),
                    DB::raw("a.id as `agency_id`")
                ])
                ->where([
                    ['a.active', '=', 1], ['b.active', '=', 1],
                    ['b.user_id_staff', '=', $user->id],
                ])
                ->orderBy('a.name')->orderBy('b.name')->get();

            $branchIds = $branches->pluck('id')->toArray();
            $cases = DB::table('conveyancing_cases as c')
                ->join('service_collections as sc', 'sc.target_id', '=', 'c.id')
                ->join('transaction_service_collections as tsc', 'tsc.service_collection_id', '=', 'sc.id')
                ->join('transactions as t', 't.id', '=', 'tsc.transaction_id')
                ->leftJoin('agencies as a', 'a.id', '=', 't.agency_id')
                ->leftJoin('agency_branches as ab', 'ab.id', '=', 't.agency_branch_id')
                ->leftJoin('solicitors as s', 's.id', '=', 'c.solicitor_id')
                ->leftJoin('solicitor_offices as so', 'so.id', '=', 'c.solicitor_office_id')
                ->leftJoin('users as u', 'u.id', '=', 't.agent_user_id')
                ->leftJoin('users as u2', 'u2.id', '=', 't.staff_user_id')
                ->leftJoin('addresses as ad', 'ad.id', '=', 't.address_id')
                ->select([
                    DB::raw('u2.id as `account_manager_user_id`'),
                    DB::raw('a.name as `Agency`'), DB::raw('ab.name as `Branch`'),
                    DB::raw('t.agency_id as `agency_id`'), DB::raw('t.agency_branch_id as `branch_id`'),
                    DB::raw('s.name as `Solicitor`'), DB::raw('so.office_name as `Office`'),
                    DB::raw('c.type as `transaction`'),
                    'c.id', 'c.reference', 'c.active', 'c.status', 't.agent_user_id',
                    DB::raw('IF(c.date_aborted IS NOT NULL, 1, 0) as `aborted`'),
                    DB::raw("IF(c.status = 'instructed_unpanelled', 'Instructed Unpanelled', c.status) as `status`"),
                    DB::raw("DATE_FORMAT(FROM_UNIXTIME(c.date_created), '%d/%m/%Y') as date_created"),
                    DB::raw('c.date_created as date_created_raw'),
                    DB::raw("CONCAT(u.forenames, ' ', u.surname) as `Agent Name`"),
                    DB::raw("CONCAT(u2.forenames, ' ', u2.surname) as `account_manager_name`"),
                    DB::raw("CONCAT(
                        COALESCE(ad.building_number, ''), ', ', ad.address_line_1, 
                        ', ', ad.town, ', ', ad.county, ', ', ad.postcode)
                    as `TransactionAddress`")
                ])
                ->where([
                    ['c.date_created', '>=', $date],
                    ['c.active', '=', 1],
                    ['sc.target_type', '=', 'conveyancing_case'],
                    ['c.reference', '!=', '']
                ])
                ->whereIn('t.agency_branch_id', $branchIds)
                ->groupBy(['c.id'])
                ->get();
        } else {
            $cases = DB::select(
                DB::raw("SELECT 
                    u2.id AS `account_manager_user_id`, 
                    a.name AS `Agency`, ab.name AS `Branch`, c.slug, 
                    t.agency_id AS `agency_id`, t.agency_branch_id AS `branch_id`, 
                    s.name AS `Solicitor`, so.office_name AS `Office`, 
                    c.type AS `transaction`, `c`.`id`, `c`.`reference`, `c`.`active`, `c`.`status`, 
                    #`c`.`panelled`, 
                    #IF(c.date_aborted IS NOT NULL, 1, 0) AS `aborted`, 
                    IF(c.status = 'instructed_unpanelled', 'Instructed Unpanelled', c.status) AS `status`, 
                    DATE_FORMAT(FROM_UNIXTIME(c.date_created), '%d/%m/%Y') AS date_created, 
                    c.date_created AS date_created_raw,
                    CONCAT(u.forenames, ' ', u.surname) AS `Agent Name`, 
                    CONCAT(u2.forenames, ' ', u2.surname) AS `account_manager_name`, 
                    CONCAT(
                        COALESCE(ad.building_number, ''), ', ', ad.address_line_1, 
                        ', ', ad.town, ', ', ad.county, ', ', ad.postcode)
                    as `TransactionAddress`, 
                    t.agent_user_id
                FROM `conveyancing_cases` AS `c`
                INNER JOIN `service_collections` as `sc` ON `sc`.`target_id` = `c`.`id`
                INNER JOIN `transaction_service_collections` as `tsc` ON `tsc`.`service_collection_id` = `sc`.`id`
                INNER JOIN `transactions` as `t` ON `t`.`id` = `tsc`.`transaction_id`
                LEFT JOIN `addresses` AS `ad` ON `ad`.`id` = `t`.`address_id`
                LEFT JOIN `agencies` AS `a` ON `a`.`id` = `t`.`agency_id`
                LEFT JOIN `agency_branches` AS `ab` ON `ab`.`id` = `t`.`agency_branch_id`
                LEFT JOIN `solicitors` AS `s` ON `s`.`id` = `c`.`solicitor_id`
                LEFT JOIN `solicitor_offices` AS `so` ON `so`.`id` = `c`.`solicitor_office_id`
                LEFT JOIN `users` AS `u` ON `u`.`id` = `t`.`agent_user_id`
                LEFT JOIN `users` AS `u2` ON `u2`.`id` = `t`.`staff_user_id`
                LEFT JOIN `transaction_customers` AS `tc` ON `tc`.`transaction_id` = `t`.`id`
                WHERE 
                    `c`.`date_created` >= $date AND `c`.`active` = 1 
                    AND `sc`.`target_type` = 'conveyancing_case' AND `c`.`reference` != ''
                GROUP BY `c`.`id`")
            );
        }
        return $cases;
    }

    private function formatTransactionPrice($price)
    {
        // if there's a comma in the price then remove it
        $price = str_replace(",", "", $price);

        // check if price contains a decimal point and it it doesn't add one
        if (strpos($price, '.') === false) {
            // add a decimal point to the price
            $price .= '.00';
        }

        // if there's a comma in the price then remove it
        $price = str_replace('.', '', $price);

        return $price;
    }

    /*
     * This method is being used on the Branch Performance dashboard and the Agency Staff/Agent dashboard
     */
    public function casesKpiFigures(
        $branchIds = false,
        $userIdAgent = false,
        $dateFrom = false,
        $dateTo = false
    ) {
        $statuses = [
            'prospect',
            'completed',
            'aborted',
            'instructed',
            'instructed_unpanelled'
        ];

        $count = [];
        if (!empty($branchIds)) {
            // logged in as an Account Manager / Lead
            $figures = $this->queryCasesKpiFiguresBranchPerformance(
                $count,
                $statuses,
                $dateFrom,
                $dateTo,
                $branchIds,
                false
            );
        } else {
            // logged in as an Agent
            $figures = $this->queryCasesKpiFiguresAgent(
                $count,
                $statuses,
                $dateFrom,
                $dateTo,
                Auth::user()->id,
                false
            );
        }
        return $figures;
    }

    private function queryCasesKpiFiguresAgent(
        $count,
        $statuses,
        $dateFrom,
        $dateTo,
        $userIdAgent
    ) {
        foreach ($statuses as $status) {
            $queryCount = DB::table('conveyancing_cases as c')
                ->join('service_collections as sc', 'sc.target_id', '=', 'c.id')
                ->join('transaction_service_collections as tsc', 'tsc.service_collection_id', '=', 'sc.id')
                ->join('transactions as t', 't.id', '=', 'tsc.transaction_id')
                ->select([
                    DB::raw('c.id'),
                    DB::raw('c.reference'),
                ])
                ->where([
                    ['c.date_created', '>=', $dateFrom],
                    ['c.date_created', '>=', $dateTo],
                    ['c.active', '=', 1],
                    ['sc.target_type', '=', 'conveyancing_case'],
                    ['c.status', '=', $status],
                    ['c.reference', '!=', ''],
                    ['t.agent_user_id', '=', $userIdAgent]
                ])
                ->count();

            $count[$status] = $queryCount;
        }

        return $count;
    }

    private function queryCasesKpiFiguresBranchPerformance(
        $count,
        $statuses,
        $dateFrom,
        $dateTo,
        $branchIds,
        $userIdAgent
    ) {
        foreach ($statuses as $status) {
            $queryCount = DB::table('conveyancing_cases as c')
                ->join('service_collections as sc', 'sc.target_id', '=', 'c.id')
                ->join('transaction_service_collections as tsc', 'tsc.service_collection_id', '=', 'sc.id')
                ->join('transactions as t', 't.id', '=', 'tsc.transaction_id')
                ->select([
                    DB::raw('c.id')
                ])
                ->where([
                    ['c.date_created', '>=', $dateFrom],
                    ['c.date_created', '<=', $dateTo],
                    ['c.active', '=', 1],
                    ['sc.target_type', '=', 'conveyancing_case'],
                    ['c.status', '=', $status],
                    ['c.reference', '!=', ''],
                ])
                ->whereIn('t.agency_branch_id', $branchIds)
                ->count();

            $count[$status] = $queryCount;
        }

        return $count;
    }
}
