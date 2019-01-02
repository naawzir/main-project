<?php
namespace App;

use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\Request;

class AgencyBranch extends Model implements AuditableContract
{
    use Auditable;

    // An Agency Branch belongs to an Agency
    public function agency()
    {
        return $this->belongsTo('App\Agency');
    }

    // An Agency Branch has one Address
    public function address()
    {
        return $this->hasOne('App\Address');
    }

    // An Agency can have Many Users
    public function agentUsers()
    {
        return $this->hasMany('App\AgentUser');
    }

    public function branchPerformanceKpiCount($branchIds, $dateFrom, $dateTo)
    {
        $statuses = [
            'prospect',
            'instructed',
            'instructed_unpanelled',
            'completed',
            'aborted'
        ];

        $count = [];

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

    public function businessOwnerBranches()
    {
        $user = Auth::user();
        $bOwner = $user->agencyUser;
        $bOwnerAgency = $bOwner->agency_id;

        $branches = DB::table('agencies as a')
            ->join('agency_branches as b', 'b.agency_id', '=', 'a.id')
            ->select([
                DB::raw('a.name as `Agency`'),
                DB::raw('b.id'),
                DB::raw('b.name as `Branch`'),
                DB::raw('a.id as `agency_id`'),
            ])
            ->where([
                ['a.active', '=', 1],
                ['b.active', '=', 1],
                ['b.agency_id', '=', $bOwnerAgency],
            ])
            ->orderBy('a.name')
            ->orderBy('b.name')
            ->get();

        return $branches;
    }

    public function branchPerformanceBranches()
    {
        $user = Auth::user();
        $branches = DB::table('agencies as a')
            ->join('agency_branches as b', 'b.agency_id', '=', 'a.id')
            ->select([
                DB::raw('a.name as `Agency`'),
                DB::raw('b.id'),
                DB::raw('b.name as `Branch`'),
                DB::raw('a.id as `agency_id`'),
            ])
            ->where([
                ['a.active', '=', 1],
                ['b.active', '=', 1],
                ['b.user_id_staff', '=', $user->id],
            ])
            ->orderBy('a.name')
            ->orderBy('b.name')
            ->get();

        return $branches;
    }

    public function branchesFilterOptions($branches)
    {
        $branchArray = [];
        foreach ($branches as $branch) {
            $branchArray[$branch->Branch] = $branch->id;
        }
        return $branchArray;
    }

    public function getMonthlyTargetsRecords($month)
    {
        $monthText = "'" . date('F', $month) . "'";
        $branchesTargets = DB::table('agencies as a')
            ->join('agency_branches as ab', 'ab.agency_id', '=', 'a.id')
            ->join('users as u', 'u.id', '=', 'ab.user_id_staff')
            ->where([
                ['a.active', '=', 1],
                ['ab.active', '=', 1],
            ])
            ->leftJoin(
                DB::raw("(
                    select 
                        abt.id, 
                        abt.date_from, 
                        abt.target,
                        abt.agency_branch_id
                    FROM targets_agency_branches as abt
                    WHERE abt.date_from = $month
                ) as Targets"),
                function (JoinClause $join) {
                    $join->on('Targets.agency_branch_id', '=', 'ab.id');
                }
            )
            ->select([
                DB::raw('Targets.id as `agency_branches_target_id`'),
                DB::raw('ab.id as `branch_id`'),
                DB::raw('a.id as `agency_id`'),
                DB::raw("CONCAT(
                    a.name, ' ',
                    ab.name) 
                as `branch_name`"),
                'Targets.target',
                DB::raw('u.id as `account_manager_user_id`'),
                DB::raw("CONCAT(
                    u.forenames, ' ',
                    u.surname) 
                as `account_manager_name`"),
                DB::raw(
                    "IF(
                        Targets.date_from != '',
                        DATE_FORMAT(FROM_UNIXTIME(Targets.date_from),
                        '%M'), 
                        {$monthText} ) as `month`"
                ),
                DB::raw("IF(Targets.date_from != '', Targets.date_from, {$month} ) as `date_from_raw`"),
            ])
            ->orderBy('ab.name', 'asc')
            ->get();

        return $branchesTargets;
    }

    public function getCasesForBranch($branchId)
    {
        $cases = DB::table('conveyancing_cases as c')
            ->join('service_collections as sc', 'sc.target_id', '=', 'c.id')
            ->join('transaction_service_collections as tsc', 'tsc.service_collection_id', '=', 'sc.id')
            ->join('transactions as t', 't.id', '=', 'tsc.transaction_id')
            ->leftJoin('agency_branches as ab', 'ab.id', '=', 't.agency_branch_id')
            ->leftJoin('solicitors as s', 's.id', '=', 'c.solicitor_id')
            ->leftJoin('solicitor_offices as so', 'so.id', '=', 'c.solicitor_office_id')
            ->leftJoin('addresses as ad', 'ad.id', '=', 't.address_id')
            ->select([
                DB::raw('s.name as `Solicitor`'),
                DB::raw('so.office_name as `Office`'),
                DB::raw('c.type as `transaction`'),
                'c.id', 'c.reference', 'c.active', 'c.status',
                DB::raw("DATE_FORMAT(FROM_UNIXTIME(c.date_created), '%d/%m/%Y') AS date_created"),
                DB::raw('c.date_created as date_created_raw'),
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
            ->where([
                ['c.active', '=', 1],
                ['t.agency_branch_id', '=', $branchId]
            ])
            ->groupBy(['c.id'])
            ->get();

        return $cases;
    }
}
