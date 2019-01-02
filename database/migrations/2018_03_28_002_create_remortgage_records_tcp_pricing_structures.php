<?php

//use App\PricingStructure;
use Illuminate\Database\Migrations\Migration;

class CreateRemortgageRecordsTcpPricingStructures extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     *
     *
     */
    public function up()
    {
        /*$buyingPricingStructures = DB::table('fee_structures AS fs')
            ->join('pricing_structures as ps', 'ps.fee_structure_id', '=', 'fs.id')
            ->where('ps.case_type', 'buying')
            ->orderBy('ps.id', 'asc')
            ->get();

        foreach($buyingPricingStructures as $buyingPricingStructure) {

            $pricingStructureModel = new PricingStructure;
            $pricingStructureModel
                ->create([

                    'fee_structure_id' => $buyingPricingStructure->fee_structure_id,
                    'start' => $buyingPricingStructure->start,
                    'end' => $buyingPricingStructure->end,
                    'legal_fee' => $buyingPricingStructure->legal_fee,
                    'referral_fee' => $buyingPricingStructure->referral_fee,
                    'agent_referral_fee' => $buyingPricingStructure->agent_referral_fee,
                    'active' => $buyingPricingStructure->active,
                    'case_type' => 'remortgage',

                ]);

        }*/

    }

}
