<?php

namespace App\Http\Controllers;

use App\Agency;
use App\Note;
use App\ConveyancingCase;
use App\AgencyBranch;
use App\ServiceCollection;
use App\TransactionServiceCollection;
use App\Transaction;
use App\TargetsAgencyBranch;
use App\Notifications\AgencyBranchContact;
use App\User;
use App\StaffUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use DB;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Contracts\Encryption\DecryptException;
use Yajra\Datatables\Datatables;

class BranchPerformanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        $branchModel = new AgencyBranch;
        $branches = $branchModel->branchPerformanceBranches();
        $branchIds = $branchModel->branchesFilterOptions($branches);

        $dateFrom = strtotime('midnight first day of this month');
        $dateTo = time();

        $casesModel = new ConveyancingCase;
        $kpis = $casesModel->casesKpiFigures($branchIds, false, $dateFrom, $dateTo);

        $userStaff = new StaffUser;
        $agencyModel = new Agency;

        $data = [
            'user' => $user,
            'agencies' => $agencyModel->activeAgencies(),
            'branches' => $branches,
            'kpis' => (object)$kpis,
            'accountManagers' => $userStaff->getAccMans(),
        ];

        $view = 'branches.performance.index';

        return view($view, $data);
    }

    public function branchesRecords()
    {
        /** @var $user User */
        $user = Auth::user();

        $branches = $this->branchPerformanceBranchesList();

        $branchListing = [];
        $i = 0;
        $numberOfDays = workingDaysInMonth($includeBankHolidays = true);
        $currentWD = currentWorkingDayInCurrentMonth($includeBankHolidays = true);

        foreach ($branches as $branch) {
            $dailyTarget = $branch->target / $numberOfDays;
            $actualDailyRunRate = $branch->achieved_to_date / $currentWD;
            $predictedFinish = number_format($actualDailyRunRate, 2, '.', '') * $numberOfDays;
            $risk = $predictedFinish - $branch->target;

            if ($branch->achieved_to_date == 0) {
                $branch->achieved_to_date = 0;
            }

            $branchListing[$i]['branch_name'] = $branch->branch_name;
            $branchListing[$i]['target'] = $branch->target;
            $branchListing[$i]['achieved_to_date'] = $branch->achieved_to_date;
            $branchListing[$i]['daily_target'] = ceil($dailyTarget);
            $branchListing[$i]['actual_daily_run_rate'] = ceil($actualDailyRunRate);
            $branchListing[$i]['predicted_finish'] = ceil($predictedFinish);
            $branchListing[$i]['risk'] = ceil($risk);
            $branchListing[$i]['last_contact_date'] = $branch->last_contact_date;
            $branchListing[$i]['branch_id'] = $branch->branch_id;
            $branchListing[$i]['date_created'] = $branch->date_created;
            $branchListing[$i]['date_created_raw'] = $branch->date_created_raw;
            $branchListing[$i]['agency_id'] = $branch->agency_id;
            $i++;
        }

        return Datatables::of($branchListing)->make(true);
    }

    public function monthlyTargetsRecords(Request $request)
    {
        $month = $request->month;
        $branchModel = new AgencyBranch;
        return Datatables::of($branchModel->getMonthlyTargetsRecords($month))->make(true);
    }

    private function branchPerformanceKpiCountBranch($branchId, $dateFrom, $dateTo)
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
            $queryCount = ConveyancingCase::select([
                'conveyancing_cases.id'
            ])->where([
                ['conveyancing_cases.active', '=', 1],
                ['conveyancing_cases.status', '=', $status],
                ['conveyancing_cases.date_created', '>=', 0],
                ['conveyancing_cases.date_created', '<=', $dateTo],
                ['t.agency_branch_id', '=', $branchId]
            ])
                ->leftJoin('service_collections as sc', 'sc.target_id', '=', 'conveyancing_cases.id')
                ->leftJoin('transaction_service_collections as tsc', 'tsc.service_collection_id', '=', 'sc.id')
                ->leftJoin('transactions as t', 't.id', '=', 'tsc.transaction_id')
                ->count();

            $count[$status] = $queryCount;
        }

        return $count;
    }

    private function branchPerformanceBranchesList()
    {
        $date = strtotime('midnight first day of this month');

        $user = Auth::user();
        $branches = DB::table('agencies as a')
            ->join('agency_branches as b', 'b.agency_id', '=', 'a.id')
            ->leftJoin('targets_agency_branches as abt', 'abt.agency_branch_id', '=', 'b.id')
            ->leftJoin(
                DB::raw("(
                    select 
                        n.date_created as `Date`, 
                        n.target_id as `Branch` 
                    FROM notes as n
                    WHERE n.target = 'agency-branch'
                    GROUP BY n.target_id 
                    ORDER BY n.date_created DESC
                ) as LastNote"),
                function (JoinClause $join) {
                    $join->on('LastNote.Branch', '=', 'b.id');
                }
            )
            ->leftJoin(
                DB::raw("(
                    select
                        count(c.id) as `achieved_to_date`,
                        t.agency_branch_id as `Branch`,
                        DATE_FORMAT(FROM_UNIXTIME(c.date_created), '%d/%m/%Y') as `date_created`,
                        c.date_created as `date_created_raw`
                    FROM conveyancing_cases as c
                    LEFT JOIN `service_collections` as `sc` ON `sc`.`target_id` = `c`.`id`
                    LEFT JOIN `transaction_service_collections` as `tsc` ON `tsc`.`service_collection_id` = `sc`.`id`
                    LEFT JOIN `transactions` as `t` ON `t`.`id` = `tsc`.`transaction_id`
                    WHERE c.date_created >= {$date}
                    AND c.status = 'instructed'
                    GROUP BY t.agency_branch_id
                ) as `Achieved`"),
                function (JoinClause $join) {
                    $join->on('Achieved.Branch', '=', 'b.id');
                }
            )
            ->select([
                DB::raw("CONCAT(a.name, ' ', b.name) as `branch_name`"),
                DB::raw('a.id as `agency_id`'),
                DB::raw('b.id as `branch_id`'),
                //DB::raw('COALESCE(abt.target, \'Not set\') as `target`'),
                DB::raw('COALESCE(abt.target, 0) as `target`'),
                DB::raw('FROM_UNIXTIME(`LastNote`.`Date`,\'%d/%m/%Y\') as `last_contact_date`'),
                DB::raw('`achieved_to_date`'),
                DB::raw('Achieved.date_created'),
                DB::raw('Achieved.date_created_raw'),
            ])
            ->where([
                ['a.active', '=', 1],
                ['b.active', '=', 1],
                ['b.user_id_staff', '=', $user->id]

            ])
            ->whereBetween('abt.date_from', [strtotime('midnight first day of this month'), time()])
            ->orderBy('a.name')
            ->orderBy('b.name')
            ->get();

        return $branches;
    }

    private function branchTargetKPIs($branchId)
    {
        $date = strtotime('midnight first day of this month');
        $branch = DB::table('agency_branches as b')
            ->leftJoin('targets_agency_branches as abt', 'abt.agency_branch_id', '=', 'b.id')
            ->leftJoin(
                DB::raw("(
                    select
                        count(c.id) as `achieved_to_date`,
                        t.agency_branch_id as `Branch`,
                        DATE_FORMAT(FROM_UNIXTIME(c.date_created), '%d/%m/%Y') as `date_created`,
                        c.date_created as `date_created_raw`
                    FROM conveyancing_cases as c
                    LEFT JOIN `service_collections` as `sc` ON `sc`.`target_id` = `c`.`id`
                    LEFT JOIN `transaction_service_collections` as `tsc` ON `tsc`.`service_collection_id` = `sc`.`id`
                    LEFT JOIN `transactions` as `t` ON `t`.`id` = `tsc`.`transaction_id`
                    WHERE c.date_created > {$date}
                    AND c.status = 'instructed'
                    GROUP BY t.agency_branch_id
                ) as `Achieved`"),
                function (JoinClause $join) {
                    $join->on('Achieved.Branch', '=', 'b.id');
                }
            )
            ->select([
                DB::raw('COALESCE(abt.target, 0) as `target`'),
                DB::raw('`achieved_to_date`'),

            ])
            ->where([
                ['b.id', '=', $branchId]
            ])
            //->whereBetween('abt.date_from', [strtotime('midnight first day of this month'), time()])
            ->where('abt.date_from', strtotime('midnight first day of this month'))
            ->first();

        return $branch ? $branch : null;
    }

    public function kpisAjax(Request $request)
    {
        $dateFrom = !empty($request['dateFrom']) ? $request['dateFrom'] : strtotime('midnight first day of this month');
        $dateTo = !empty($request['dateTo']) ? $request['dateTo'] : time();
        $kpis = null;
        $conversions = null;

        $branchModel = new AgencyBranch;

        if (!empty($request['branchId'])) {
            $branchId = $request['branchId'];
            $kpis = $this->branchPerformanceKpiCountBranch($branchId, $dateFrom, $dateTo);
            $conversions = $this->getConversions($dateFrom, $dateTo, $branchId);
        } else {
            $branches = $branchModel->branchPerformanceBranches();
            $branchIds = $branchModel->branchesFilterOptions($branches);
            $kpis = $branchModel->branchPerformanceKpiCount($branchIds, $dateFrom, $dateTo);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'branchPerformanceKpi' => $kpis,
                'conversions' => $conversions,
            ]
        ]);
    }

    public function kpisAjaxBusinessOwner(Request $request)
    {
        $dateFrom = !empty($request['dateFrom']) ? $request['dateFrom'] : strtotime('midnight first day of this month');
        $dateTo = !empty($request['dateTo']) ? $request['dateTo'] : time();
        $kpis = null;
        $conversions = null;

        $branchModel = new AgencyBranch;

        if (!empty($request['branchId'])) {
            $branchId = $request['branchId'];
            $kpis = $this->branchPerformanceKpiCountBranch($branchId, $dateFrom, $dateTo);
            $conversions = $this->getConversions($dateFrom, $branchId);
        } else {
            $branches = $branchModel->businessOwnerBranches();
            $branchIds = $branchModel->branchesFilterOptions($branches);
            $kpis = $branchModel->branchPerformanceKpiCount($branchIds, $dateFrom, $dateTo);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'branchPerformanceKpi' => $kpis,
                'conversions' => $conversions,
            ]
        ]);
    }

    private function branchTargetsKPIs($branchId)
    {
        $branchModel = new AgencyBranch;
        $branchTargetsKPIs = $this->branchTargetKPIs($branchId);
        if ($branchTargetsKPIs != null) {
            $branch = [];
            $numberOfDays = workingDaysInMonth($includeBankHolidays = true);
            $currentWD = currentWorkingDayInCurrentMonth($includeBankHolidays = true);

            $dailyTarget = $branchTargetsKPIs->target / $numberOfDays;
            $dailyTargetRounded = number_format((float)$dailyTarget, 2, '.', '');

            $actualDailyRunRate = $branchTargetsKPIs->achieved_to_date / $currentWD;
            $actualDRR = number_format((float)$actualDailyRunRate, 2, '.', '');
            $predictedFinish = $actualDailyRunRate * $numberOfDays;
            $PFRounded = number_format((float)$predictedFinish, 2, '.', '');

            $riskOriginal = $predictedFinish - $branchTargetsKPIs->target;
            $riskCeiled = ceil($riskOriginal);
            $risk = $riskCeiled == '-0' ? 0 : $riskCeiled;

            if ($branchTargetsKPIs->achieved_to_date == 0) {
                $branchTargetsKPIs->achieved_to_date = 0;
            }

            $branch['target'] = $branchTargetsKPIs->target;
            $branch['achieved_to_date'] = $branchTargetsKPIs->achieved_to_date;
            $branch['daily_target'] = ceil($dailyTargetRounded);
            $branch['actual_daily_run_rate'] = ceil($actualDRR);
            $branch['predicted_finish'] = ceil($PFRounded);
            $branch['risk'] = $risk;
        } else {
            $branch['target'] = 0;
            $branch['achieved_to_date'] = 0;
            $branch['daily_target'] = 0;
            $branch['actual_daily_run_rate'] = 0;
            $branch['predicted_finish'] = 0;
            $branch['risk'] = 0;
        }

        return $branch;
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(AgencyBranch $branch)
    {
        $user = Auth::user();

        $dateFrom = strtotime('midnight first day of this month');
        $dateTo = time();

        $kpis = $this->branchPerformanceKpiCountBranch($branch->id, $dateFrom, $dateTo);
        $agency = $branch->agency;
        $dashData = $this->getBranchPerformanceData($dateFrom, $dateTo, $branch->id);
        $branchTargetsKPIs = $this->branchTargetsKPIs($branch->id);

        $data = [
            'user' => $user,
            'agency' => $agency,
            'branch' => $branch,
            'kpis' => (object)$kpis,
            'dashData' => $dashData,
            'branchTargetsKPIs' => (object)$branchTargetsKPIs,
        ];

        $view = 'branches.performance.branch.performance-targets';

        return view($view, $data);
    }

    public function editKPITarget(Request $request)
    {
        $target = $request['target'];
        $achievedToDate = $request['achieved_to_date'];
        $numberOfDays = workingDaysInMonth($includeBankHolidays = true);
        $currentWD = currentWorkingDayInCurrentMonth($includeBankHolidays = true);

        $dailyTarget = $target / $numberOfDays;
        $dailyTarget = ceil($dailyTarget);
        $actualDailyRunRate = $achievedToDate / $currentWD;

        $predictedFinish = number_format($actualDailyRunRate, 2, '.', '') * $numberOfDays;
        $risk = $predictedFinish - $target;
        $risk = ceil($risk);

        return response()->json([
            "success" => true,
            "data" => [
                "daily_target" => $dailyTarget,
                "risk" => $risk
            ]
        ]);
    }

    public function updateKpiTarget(Request $request)
    {
        $branchId = $request['branchId'];
        $target = $request['target'];

        $abtmodel = new TargetsAgencyBranch;
        $agencyBranchesTarget = $abtmodel->updateKpiTarget($branchId, $target);

        if (!empty($agencyBranchesTarget)) {
            return response()->json([
                "success" => true
            ]);
        }
    }

    public function createTarget(Request $request)
    {
        $target = $request['target'];
        $id = $request['id'];
        $month = $request['month'];

        $values = explode('_', $id);
        $agency_id = $values[0];
        $branch_id = $values[1];

        $BranchTargetModel = new TargetsAgencyBranch;
        $agencyBranchesTarget = $BranchTargetModel->createTarget(
            $agency_id,
            $branch_id,
            $month,
            $target
        );

        return response()->json([
            'success' => true,
            'data' => [
                'target_id' => $agencyBranchesTarget->id
            ]
        ]);
    }

    public function getBranchPerformanceData($dateFrom, $dateTo, $branchId) : array
    {
        $data = [
            'conversion' => $this->getConversions($dateFrom, $dateTo, $branchId),
            'branchTargetsKPIs' => $this->getBranchTargetsKPIs($branchId),
        ];

        return $data;
    }

    private function getConversions($dateFrom, $dateTo, $branchId)
    {
        $prospectsCount = ConveyancingCase::select([
            'conveyancing_cases.id'
        ])->where([
            ['conveyancing_cases.active', '=', 1],
            ['conveyancing_cases.status', '=', 'prospect'],
            ['conveyancing_cases.date_created', '>=', $dateFrom],
            ['conveyancing_cases.date_created', '<=', $dateTo],
            ['t.agency_branch_id', '=', $branchId]
        ])
        ->leftJoin('service_collections as sc', 'sc.target_id', '=', 'conveyancing_cases.id')
        ->leftJoin('transaction_service_collections as tsc', 'tsc.service_collection_id', '=', 'sc.id')
        ->leftJoin('transactions as t', 't.id', '=', 'tsc.transaction_id')
        ->count();

        $instructedCount = ConveyancingCase::select([
            'conveyancing_cases.id'
        ])->where([
            ['conveyancing_cases.active', '=', 1],
            ['conveyancing_cases.status', '=', 'instructed'],
            ['conveyancing_cases.date_created', '>=', $dateFrom],
            ['conveyancing_cases.date_created', '<=', $dateTo],
            ['t.agency_branch_id', '=', $branchId]
        ])
        ->leftJoin('service_collections as sc', 'sc.target_id', '=', 'conveyancing_cases.id')
        ->leftJoin('transaction_service_collections as tsc', 'tsc.service_collection_id', '=', 'sc.id')
        ->leftJoin('transactions as t', 't.id', '=', 'tsc.transaction_id')
        ->count();

        $total = $prospectsCount + $instructedCount;
        $converted = !empty($total) ? round(100 / $total * $instructedCount) : 0;
        $notConverted = 100 - $converted;

        return [
            'prospectToInstructed' => [
                [
                    'amount' => $converted,
                    'color' => '#E3BB4B',
                    'desc' => 'Conversion',
                    'percent' => true
                ],

                [
                    'amount' => $notConverted,
                    'color' => '#D8D6D7'
                ],
            ],
        ];
    }

    private function getBranchTargetsKPIs($branchId)
    {
        $branchTargetsKPIs = $this->branchTargetKPIs($branchId);

        if ($branchTargetsKPIs != null) {
            $instructions = $branchTargetsKPIs->target - $branchTargetsKPIs->achieved_to_date;
            $instructions = $instructions < 0 ? 0 : $instructions;
            $numberOfDays = workingDaysInMonth($includeBankHolidays = true);
            $workingDay = currentWorkingDayInCurrentMonth($includeBankHolidays = true);
            $dailyTarget = $branchTargetsKPIs->target / $numberOfDays;
            $dailyTarget = ceil($dailyTarget);
            $actualDailyRunRate = $branchTargetsKPIs->achieved_to_date / $workingDay;
            $actualDailyRunRate = ceil($actualDailyRunRate);
            $outstandingTargets = $dailyTarget - $actualDailyRunRate;
            $outstandingTargets = $outstandingTargets <= 0 ? 0 : $outstandingTargets;

            return [
                        'branchMonthlyTarget' => [
                            [
                                'amount' => $branchTargetsKPIs->target,
                                'color' => '#D4DCB4',
                                'desc' => 'Target'
                            ],
                        ],
                        'branchInstructionsMTD' => [
                            [
                                'amount' => $branchTargetsKPIs->achieved_to_date,
                                'color' => '#E3BB4B',
                                'desc' => 'Instructions MTD'
                            ],

                            [
                                'amount' => $instructions,
                                'color' => '#D8D6D7'
                            ],
                        ],
                        'branchDailyTarget' => [
                            [
                                'amount' => $dailyTarget,
                                'color' => '#D4DCB4',
                                'desc' => 'Target'
                            ],
                        ],
                        'branchActualDailyRunRate' => [
                            [
                                'amount' => $actualDailyRunRate,
                                'color' => '#E3BB4B',
                                'desc' => 'Actual'
                            ],
                            [
                                'amount' => $outstandingTargets,
                                'color' => '#D8D6D7'
                            ],
                        ],
            ];
        } else {
            return [
                'branchMonthlyTarget' => [
                    [
                        'amount' => 0,
                        'color' => '#D4DCB4',
                        'desc' => 'Target'
                    ],
                ],
                'branchInstructionsMTD' => [
                    [
                        'amount' => 0,
                        'color' => '#E3BB4B',
                        'desc' => 'Instructions MTD'
                    ],

                    [
                        'amount' => 0,
                        'color' => '#D8D6D7'
                    ],
                ],
                'branchDailyTarget' => [
                    [
                        'amount' => 0,
                        'color' => '#D4DCB4',
                        'desc' => 'Target'
                    ],
                ],
                'branchActualDailyRunRate' => [
                    [
                        'amount' => 0,
                        'color' => '#E3BB4B',
                        'desc' => 'Actual'
                    ],
                    [
                        'amount' => 0,
                        'color' => '#D8D6D7'
                    ],
                ],
            ];
        }
    }
}
