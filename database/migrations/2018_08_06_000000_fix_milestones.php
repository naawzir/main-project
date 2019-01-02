<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixMilestones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('tm_milestones') && Schema::hasTable('milestones')) {
            
            // Add some Missing Milestones
            DB::statement("INSERT INTO `tm_milestones` (number,name,case_type) VALUES (46,'searches_received','purchase')");
            DB::statement("INSERT INTO `tm_milestones` (number,name,case_type) VALUES (66,'enquiries_raised','sale')");
            DB::statement("INSERT INTO `tm_milestones` (number,name,case_type) VALUES (153,'enquiries_answered','sale')");

            //Update Milestones with Incorrect Numbers
            DB::statement("UPDATE `tm_milestones` SET number = '117' WHERE name = 'welcome_call' AND case_type = 'purchase'");
            DB::statement("UPDATE `tm_milestones` SET number = '118' WHERE name = 'welcome_pack_sent' AND case_type = 'purchase'");
            DB::statement("UPDATE `tm_milestones` SET number = '122' WHERE name = 'id_verified' AND case_type = 'purchase'");
            DB::statement("UPDATE `tm_milestones` SET number = '115' WHERE name = 'instruction_confirmed' AND case_type = 'purchase'");
            DB::statement("UPDATE `tm_milestones` SET number = '119' WHERE name = 'sale_memo_received' AND case_type = 'purchase'");
            DB::statement("UPDATE `tm_milestones` SET number = '126' WHERE name = 'contract_pack_requested' AND case_type = 'purchase'");
            DB::statement("UPDATE `tm_milestones` SET number = '120' WHERE name = 'account_money_received' AND case_type = 'purchase'");
            DB::statement("UPDATE `tm_milestones` SET number = '127' WHERE name = 'contract_pack_received' AND case_type = 'purchase'");
            DB::statement("UPDATE `tm_milestones` SET number = '128' WHERE name = 'searches_ordered' AND case_type = 'purchase'");
            DB::statement("UPDATE `tm_milestones` SET number = '129' WHERE name = 'title_investigated' AND case_type = 'purchase'");
            DB::statement("UPDATE `tm_milestones` SET number = '116' WHERE name = 'mortgage_offer_received' AND case_type = 'purchase'");
            DB::statement("UPDATE `tm_milestones` SET number = '133' WHERE name = 'additional_queries_answered' AND case_type = 'purchase'");
            DB::statement("UPDATE `tm_milestones` SET number = '134' WHERE name = 'signed_documents_received' AND case_type = 'purchase'");
            DB::statement("UPDATE `tm_milestones` SET number = '123' WHERE name = 'ready_to_exchange' AND case_type = 'purchase'");
            DB::statement("UPDATE `tm_milestones` SET number = '124' WHERE name = 'exchange_of_contracts' AND case_type = 'purchase'");
            DB::statement("UPDATE `tm_milestones` SET number = '135' WHERE name = 'completion' AND case_type = 'purchase'");
            DB::statement("UPDATE `tm_milestones` SET number = '130' WHERE name = 'priority_expiry' AND case_type = 'purchase'");
            DB::statement("UPDATE `tm_milestones` SET number = '131' WHERE name = 'purchase_completed' AND case_type = 'purchase'");
            DB::statement("UPDATE `tm_milestones` SET number = '117' WHERE name = 'welcome_call' AND case_type = 'sale'");
            DB::statement("UPDATE `tm_milestones` SET number = '118' WHERE name = 'welcome_pack_sent' AND case_type = 'sale'");
            DB::statement("UPDATE `tm_milestones` SET number = '122' WHERE name = 'id_verified' AND case_type = 'sale'");
            DB::statement("UPDATE `tm_milestones` SET number = '115' WHERE name = 'instruction_confirmed' AND case_type = 'sale'");
            DB::statement("UPDATE `tm_milestones` SET number = '119' WHERE name = 'sale_memo_received' AND case_type = 'sale'");
            DB::statement("UPDATE `tm_milestones` SET number = '120' WHERE name = 'account_money_received' AND case_type = 'sale'");
            DB::statement("UPDATE `tm_milestones` SET number = '121' WHERE name = 'title_information_ordered' AND case_type = 'sale'");
            DB::statement("UPDATE `tm_milestones` SET number = '136' WHERE name = 'contract_pack_issued' AND case_type = 'sale'");
            DB::statement("UPDATE `tm_milestones` SET number = '134' WHERE name = 'signed_documents_received' AND case_type = 'sale'");
            DB::statement("UPDATE `tm_milestones` SET number = '123' WHERE name = 'ready_to_exchange' AND case_type = 'sale'");
            DB::statement("UPDATE `tm_milestones` SET number = '124' WHERE name = 'exchange_of_contracts' AND case_type = 'sale'");
            DB::statement("UPDATE `tm_milestones` SET number = '135' WHERE name = 'completion' AND case_type = 'sale'");
            DB::statement("UPDATE `tm_milestones` SET number = '125' WHERE name = 'sale_completed' AND case_type = 'sale'");

            // Update the `case_type` field to show the same in both the `milestones` table as it does in the `tm_milestones` table
            DB::statement("UPDATE `milestones` SET case_type = 'purchase' WHERE case_type = 'buying'");
            DB::statement("UPDATE `milestones` SET case_type = 'sale' WHERE case_type = 'selling'");

            // Updates the `milestones.milestone` field to have the ID of a milestone rather than its number
            DB::statement("UPDATE `milestones` as m, (SELECT id, number, case_type FROM `tm_milestones`) as temp SET m.milestone = temp.id WHERE temp.number = m.milestone AND temp.case_type = m.case_type");
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
