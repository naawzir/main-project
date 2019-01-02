<?php
namespace App;

use App\Notifications\AgencyBranchContact;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Cases extends Model implements AuditableContract
{
    use Auditable;

    public function agency()
    {
        return $this->belongsTo('App\Agency');
    }

    public function userStaff()
    {
        return $this->hasOne('App\User', 'id', 'user_id_staff');
    }

    public function solicitor()
    {
        return $this->hasOne('App\Solicitor', 'id', 'solicitor_id');
    }

    public function solicitorOffice()
    {
        return $this->hasOne('App\SolicitorOffice', 'id', 'solicitor_office_id');
    }

    public function address()
    {
        return $this->hasOne('App\Address', 'id', 'address_id');
    }

    public function agentUpdateRequests()
    {
        return $this->hasMany('App\AgentUpdateRequest', 'case_id', 'id');
    }

    public function getAdminAssignedTo()
    {
        if (!empty($this->user_id_staff)) {
            $userModel = new User;
            $user =
                $userModel->find($this->user_id_staff);
            //->where('id', $this->user_id_staff)
            //->orderBy('forenames', 'asc')
            //->first();

            if (!empty($user)) {
                return $user->fullname();
            }

            return false;
        }

        return false;
    }

    public function getAgentAssignedTo()
    {
        if (!empty($this->user_id_agent)) {
            $userModel = new User;
            $user =
                $userModel->find($this->user_id_agent);
            //->where('id', $this->user_id_agent)
            //->orderBy('forenames', 'asc')
            //->first();

            if (!empty($user)) {
                return $user->fullname();
            }

            return false;
        }

        return false;
    }

    public function getLeadSource(): ?string
    {
        $leadSources = [
            'buyer_sstc' => 'Buyer SSTC',
            'seller_sstc' => 'Seller SSTC',
            'seller_new_take_on' => 'Seller New Take On',
            'seller_on_market_already' => 'Seller On Market Already',
            'seller_welcome_call' => 'Seller Welcome Call',
            'none' => 'None',
        ];

        if (array_key_exists($this->lead_source, $leadSources)) {
            return $leadSources[$this->lead_source];
        }

        return null;
    }

    public function getCasesForAgencyUsers(
        $date,
        $user,
        $userAgent,
        $userIdAgent
    ) {
        $userRole = $user->userRole;
        if (!empty($userIdAgent)) {
            $whereClauseColumn = 'c.user_id_agent';
            $whereClauseValue = [Auth::user()->id];
        } elseif ($userRole->id === 7) { // Business Owner
            // get branches for this agency
            $branchModel = new AgencyBranch;
            $branches = $branchModel->businessOwnerBranches();
            $branchArray = [];
            foreach ($branches as $branch) {
                $branchArray[] = $branch->id;
            }
            $whereClauseColumn = 'c.agency_branch_id';
            $whereClauseValue = $branchArray;
        } elseif ($userRole->group === 'Agent' || $userRole->id === 8) {
            $whereClauseColumn = 'c.agency_branch_id';
            $whereClauseValue = [$userAgent->agency_branch_id];
        }

        $cases = DB::table('conveyancing_cases AS c')
            ->leftJoin('agencies as a', 'a.id', '=', 'c.agency_id')
            ->leftJoin('agency_branches as ab', 'ab.id', '=', 'c.agency_branch_id')
            ->leftJoin('solicitors as s', 's.id', '=', 'c.solicitor_id')
            ->leftJoin('solicitor_offices as so', 'so.id', '=', 'c.solicitor_office_id')
            ->leftJoin('users as u', 'u.id', '=', 'c.user_id_agent')
            ->leftJoin('users as u2', 'u2.id', '=', 'c.user_id_staff')
            ->leftJoin('addresses as ad', 'ad.id', '=', 'c.address_id')
            ->select([
                DB::raw('u2.id as `account_manager_user_id`'),
                DB::raw("CONCAT(u2.forenames, ' ', u2.surname) as `account_manager_name`"),
                DB::raw('c.agency_branch_id as `branch_id`'),
                DB::raw('a.name as `Agency`'),
                DB::raw('ab.name as `Branch`'),
                DB::raw('ab.name as `Branch`'),
                DB::raw('c.user_id_agent'),
                DB::raw('s.name as `Solicitor`'),
                DB::raw('so.office_name as `Office`'),
                DB::raw('c.type as `transaction`'),
                'c.id',
                'c.reference',
                'c.active',
                'c.status',
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
            ->whereIn('c.active', [1])
            ->where('c.date_created', '>=', $date)
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
                ->where('a.active', 1)
                ->where('b.active', 1)
                ->where('b.user_id_staff', $user->id)
                ->orderBy('a.name')
                ->orderBy('b.name')
                ->get();
            $branchIds = $branches->pluck('id')->toArray();
            $cases = DB::table('conveyancing_cases as c')
                ->leftJoin('agencies as a', 'a.id', '=', 'c.agency_id')
                ->leftJoin('agency_branches as ab', 'ab.id', '=', 'c.agency_branch_id')
                ->leftJoin('solicitors as s', 's.id', '=', 'c.solicitor_id')
                ->leftJoin('solicitor_offices as so', 'so.id', '=', 'c.solicitor_office_id')
                ->leftJoin('users as u', 'u.id', '=', 'c.user_id_agent')
                ->leftJoin('users as u2', 'u2.id', '=', 'c.user_id_staff')
                ->leftJoin('addresses as ad', 'ad.id', '=', 'c.address_id')
                ->select([
                    DB::raw('u2.id as `account_manager_user_id`'),
                    DB::raw('a.name as `Agency`'),
                    DB::raw('ab.name as `Branch`'),
                    DB::raw('c.agency_id as `agency_id`'),
                    DB::raw('c.agency_branch_id as `branch_id`'),
                    DB::raw('s.name as `Solicitor`'),
                    DB::raw('so.office_name as `Office`'),
                    DB::raw('c.type as `transaction`'),
                    'c.id', 'c.reference', 'c.active', 'c.status', 'c.panelled', 'c.user_id_agent',
                    DB::raw('IF(c.date_aborted IS NOT NULL, 1, 0) as `aborted`'),
                    DB::raw("IF(c.status = 'instructed_unpanelled', 'Instructed Unpanelled', c.status) as `status`"),
                    DB::raw("DATE_FORMAT(FROM_UNIXTIME(c.date_created), '%d/%m/%Y') as date_created"),
                    DB::raw('c.date_created as date_created_raw'),
                    DB::raw("CONCAT(u.forenames, ' ', u.surname) as `Agent Name`"),
                    DB::raw("CONCAT(u2.forenames, ' ', u2.surname) as `account_manager_name`"),
                    DB::raw("CONCAT(
                        ad.building_number, ', ', ad.address_line_1, ', ', ad.town, ', ', ad.county, ', ', ad.postcode)
                    as `TransactionAddress`")
                ])
                ->where('c.date_created', '>', $date)
                ->whereIn('c.agency_branch_id', $branchIds)
                ->where('c.active', 1)
                #->where('a.active', 1)
                #->where('ab.active', 1)
                ->groupBy(['c.id'])
                ->get();
        } else {
            $cases = DB::select(
                DB::raw("SELECT 
                    u2.id AS `account_manager_user_id`, 
                    a.name AS `Agency`, 
                    ab.name AS `Branch`, 
                    c.slug, 
                    c.agency_id AS `agency_id`, 
                    c.agency_branch_id AS `branch_id`, 
                    s.name AS `Solicitor`, 
                    so.office_name AS `Office`, 
                    c.type AS `transaction`, 
                    `c`.`id`, 
                    `c`.`reference`, 
                    `c`.`active`, `c`.`status`, 
                    `c`.`panelled`, 
                    IF(c.date_aborted IS NOT NULL, 1, 0) AS `aborted`, 
                    IF(c.status = 'instructed_unpanelled', 'Instructed Unpanelled', c.status) AS `status`, 
                    DATE_FORMAT(FROM_UNIXTIME(c.date_created), '%d/%m/%Y') AS date_created, 
                    c.date_created AS date_created_raw,
                    CONCAT(u.forenames, ' ', u.surname) AS `Agent Name`, 
                    CONCAT(u2.forenames, ' ', u2.surname) AS `account_manager_name`, 
                    CONCAT(ad.building_number, ', ', ad.address_line_1, ', ', 
                    ad.town, ', ', ad.county, ', ', ad.postcode)
                    AS `TransactionAddress`, 
                    c.user_id_agent
                FROM `conveyancing_cases` AS `c`
                LEFT JOIN `agencies` AS `a` ON `a`.`id` = `c`.`agency_id`
                LEFT JOIN `agency_branches` AS `ab` ON `ab`.`id` = `c`.`agency_branch_id`
                LEFT JOIN `solicitors` AS `s` ON `s`.`id` = `c`.`solicitor_id`
                LEFT JOIN `solicitor_offices` AS `so` ON `so`.`id` = `c`.`solicitor_office_id`
                LEFT JOIN `users` AS `u` ON `u`.`id` = `c`.`user_id_agent`
                LEFT JOIN `users` AS `u2` ON `u2`.`id` = `c`.`user_id_staff`
                LEFT JOIN `addresses` AS `ad` ON `ad`.`id` = `c`.`address_id`
                LEFT JOIN `transaction_customers` AS `tc` ON `tc`.`case_id` = `c`.`id`
                WHERE 
                    `c`.`date_created` > $date 
                    AND `c`.`active` = 1 
                    #AND `a`.`active` = 1 
                    #AND `ab`.`active` = 1
                GROUP BY `c`.`id`")
            );
        }
        return $cases;
    }

    public function getCasesForBranch($branchId)
    {
        $cases = DB::table('conveyancing_cases as c')
            ->join('agency_branches as ab', 'ab.id', '=', 'c.agency_branch_id')
            ->leftJoin('solicitors as s', 's.id', '=', 'c.solicitor_id')
            ->leftJoin('solicitor_offices as so', 'so.id', '=', 'c.solicitor_office_id')
            ->leftJoin('addresses as ad', 'ad.id', '=', 'c.address_id')
            ->select([
                DB::raw('s.name as `Solicitor`'),
                DB::raw('so.office_name as `Office`'),
                DB::raw('c.type as `transaction`'),
                'c.id',
                'c.reference',
                'c.active',
                'c.status',
                'c.panelled',
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
                ['ab.id', '=', $branchId]

            ])
            ->groupBy(['c.id'])
            ->get();

        return $cases;
    }

    public function validation($request, $field, $value)
    {
        if ($field === 'type') {
            $request->validate([

                'type' => 'required'

            ]);
        } elseif ($field === 'lead_source') {
            $request->validate([

                'lead_source' => 'required|min:2|'

            ]);
        } elseif ($field === 'price') {
            $request->validate([

                'price' => 'required'

            ]);

            $value = $this->formatTransactionPrice($value);
        } elseif ($field === 'tenure') {
            $request->validate([

                'tenure' => 'required'

            ]);
        } elseif ($field === 'mortgage') {
            $request->validate([

                'mortgage' => 'required'

            ]);
        } elseif ($field === 'searches_required') {
            $request->validate([

                'searches_required' => 'required'

            ]);
        } elseif ($field === 'aml_fees_paid') {
            $value = !empty($value) ? 1 : 0;
        }

        return $value;
    }

    public function formatTransactionPrice($price)
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
        $date = false
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
                $date,
                $branchIds,
                false
            );
        } else {
            // logged in as an Agent
            $figures = $this->queryCasesKpiFiguresAgent(
                $count,
                $statuses,
                $date,
                Auth::user()->id,
                false
            );
        }
        return $figures;
    }

    private function queryCasesKpiFiguresAgent(
        $count,
        $statuses,
        $date,
        $userIdAgent,
        $branchIds
    ) {
        foreach ($statuses as $status) {
            $queryCount =
                ConveyancingCase::where([
                    ['active', '=', 1],
                    ['status', '=', $status],
                    ['date_created', '>', $date],
                    ['user_id_agent', '=', $userIdAgent]
                ])->count();

            $count[$status] = $queryCount;
        }
        return $count;
    }

    private function queryCasesKpiFiguresBranchPerformance(
        $count,
        $statuses,
        $date,
        $branchIds,
        $userIdAgent
    ) {
        foreach ($statuses as $status) {
            $queryCount =
                ConveyancingCase::where([
                    ['active', '=', 1],
                    ['status', '=', $status],
                    ['date_created', '>', $date]
                ])
                    ->whereIn('agency_branch_id', $branchIds)
                    ->count();

            $count[$status] = $queryCount;
        }
        return $count;
    }
}
