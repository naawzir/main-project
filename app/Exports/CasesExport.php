<?php

namespace App\Exports;

use App\ConveyancingCase;
use App\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Sheet;
use DB;
use Maatwebsite\Excel\Concerns\WithEvents;

class CasesExport implements FromCollection, WithHeadings, WithColumnFormatting, WithEvents
{
    private $agencyId;
    private $viewId;
    private $statusId;
    private $transactionId;

    public function __construct(
        string $agencyId = null,
        string $viewId = null,
        string $status = null,
        string $transaction = null
    ) {
        $this->agencyId = $agencyId;
        $this->viewId = $viewId;
        $this->status = $status;
        $this->transaction = $transaction;
    }

    /**
     * @return array
     */
    public function columnFormats(): array
    {
        return [
            'A' => '',
            'B' => '',
        ];
    }

    public function collection()
    {
        $activeCase = ['c.active', '=', 1];
        $activeAgency = ['a.active', '=', 1];
        $activeBranch = ['ab.active', '=', 1];

        if (!empty($this->status)) {
            $status = ['c.status', '=', $this->status];
        } else {
            $status = ['c.status', '!=', ''];
        }

        if (!empty($this->agencyId)) {
            $agencyId = ['t.agency_id', '=', $this->agencyId];
        } else {
            $agencyId = ['t.agency_id', '!=', ''];
        }

        if (!empty($this->viewId)) {
            $viewId = ['t.staff_user_id', '=', $this->viewId];
        } else {
            $viewId = ['t.staff_user_id', '!=', ''];
        }

        if (!empty($this->transaction)) {
            $transaction = ['c.type', '=', $this->transaction];
        } else {
            $transaction = ['c.type', '!=', ''];
        }

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
            ->leftJoin('transaction_customers as tc', 'tc.transaction_id', '=', 't.id')
            ->select([
                DB::raw("DATE_FORMAT(FROM_UNIXTIME(c.date_created), '%d/%m/%Y') as date_created"),
                'c.reference',
                'c.status',
                DB::raw('c.type as `transaction`'),
                DB::raw("CONCAT(
                        ad.building_number, ', ', ad.address_line_1, ', ', ad.town, ', ', ad.county, ', ', ad.postcode
                    )
                    as `TransactionAddress`"),
                DB::raw("CONCAT(u2.forenames, ' ', u2.surname) as `account_manager_name`"),
                DB::raw('s.name as `Solicitor`'),
                DB::raw('so.office_name as `Office`'),
                DB::raw('a.name as `Agency`'),
                DB::raw('ab.name as `Branch`'),
                DB::raw("CONCAT(u.forenames, ' ', u.surname) as `Agent Name`")
            ])
            ->where([
                $status,
                $agencyId,
                $viewId,
                $transaction,
                $activeCase,
                $activeAgency,
                $activeBranch
            ])
            ->orderBy('c.date_created', 'desc')
            ->groupBy(['c.id'])
            ->get();

        return $cases;
    }

    public function headings(): array
    {
        return [
            'Date Created',
            'Reference',
            'Status',
            'Transaction',
            'Address',
            'Account Manager Name',
            'Solicitor',
            'Office',
            'Agency',
            'Branch',
            'Agent Name',
        ];
    }

    public function registerEvents() : array
    {
        Sheet::macro('styleCells', function (Sheet $sheet, string $cellRange, array $style) {
            $sheet->getDelegate()->getStyle($cellRange)->applyFromArray($style);
        });

        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getStyle('A1:K1')->applyFromArray([
                    'font' => [
                        'bold' => true],
                    $event->sheet->getColumnDimension('A')->setAutoSize(true),
                    $event->sheet->getColumnDimension('B')->setAutoSize(true),
                    $event->sheet->getColumnDimension('C')->setAutoSize(true),
                    $event->sheet->getColumnDimension('D')->setAutoSize(true),
                    $event->sheet->getColumnDimension('E')->setAutoSize(true),
                    $event->sheet->getColumnDimension('F')->setAutoSize(true),
                    $event->sheet->getColumnDimension('G')->setAutoSize(true),
                    $event->sheet->getColumnDimension('H')->setAutoSize(true),
                    $event->sheet->getColumnDimension('I')->setAutoSize(true),
                    $event->sheet->getColumnDimension('J')->setAutoSize(true),
                    $event->sheet->getColumnDimension('K')->setAutoSize(true)]);
            }
        ];
    }
}
