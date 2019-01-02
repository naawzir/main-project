<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveTcpPrefixResolveissue extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('addresses')) {
            Schema::rename('tcp_addresses', 'addresses');
        }

        if (!Schema::hasTable('agencies')) {
            Schema::rename('tcp_agencies', 'agencies');
        }

        if (!Schema::hasTable('agency_branches')) {
            Schema::rename('tcp_agency_branches', 'agency_branches');
        }

        if (!Schema::hasTable('audits')) {
            Schema::rename('tcp_audits', 'audits');
        }

        if (!Schema::hasTable('caches')) {
            Schema::rename('tcp_caches', 'caches');
        }

        if (!Schema::hasTable('cases')) {
            Schema::rename('tcp_cases', 'cases');
        }

        if (!Schema::hasTable('cases_history')) {
            Schema::rename('tcp_cases_history', 'cases_history');
        }

        if (!Schema::hasTable('case_disbursements')) {
            Schema::rename('tcp_case_disbursements', 'case_disbursements');
        }

        if (!Schema::hasTable('case_fees')) {
            Schema::rename('tcp_case_fees', 'case_fees');
        }

        if (!Schema::hasTable('disbursements')) {
            Schema::rename('tcp_disbursements', 'disbursements');
        }

        if (!Schema::hasTable('disbursements_solicitors')) {
            Schema::rename('tcp_disbursements_solicitors', 'disbursements_solicitors');
        }

        if (!Schema::hasTable('documents')) {
            Schema::rename('tcp_documents', 'documents');
        }

        if (!Schema::hasTable('features')) {
            Schema::rename('tcp_features', 'features');
        }

        if (!Schema::hasTable('feature_users')) {
            Schema::rename('tcp_feature_users', 'feature_users');
        }

        if (!Schema::hasTable('feedback_completions')) {
            Schema::rename('tcp_feedback_completions', 'feedback_completions');
        }

        if (!Schema::hasTable('fee_structures')) {
            Schema::rename('tcp_fee_structures', 'fee_structures');
        }

        if (!Schema::hasTable('instructions')) {
            Schema::rename('tcp_instructions', 'instructions');
        }

        if (!Schema::hasTable('instruction_documents')) {
            Schema::rename('tcp_instruction_documents', 'instruction_documents');
        }

        if (!Schema::hasTable('instruction_recipients')) {
            Schema::rename('tcp_instruction_recipients', 'instruction_recipients');
        }

        if (!Schema::hasTable('last_case_reference')) {
            Schema::rename('tcp_last_case_reference', 'last_case_reference');
        }

        if (!Schema::hasTable('logins')) {
            Schema::rename('tcp_logins', 'logins');
        }

        if (!Schema::hasTable('marketing_requests')) {
            Schema::rename('tcp_marketing_requests', 'marketing_requests');
        }

        if (!Schema::hasTable('marketing_resources')) {
            Schema::rename('tcp_marketing_resources', 'marketing_resources');
        }

        if (!Schema::hasTable('milestones')) {
            Schema::rename('tcp_milestones', 'milestones');
        }

        if (!Schema::hasTable('notes')) {
            Schema::rename('tcp_notes', 'notes');
        }

        if (!Schema::hasTable('password_resets')) {
            Schema::rename('tcp_password_resets', 'password_resets');
        }

        if (!Schema::hasTable('permissions')) {
            Schema::rename('tcp_permissions', 'permissions');
        }

        if (!Schema::hasTable('additional_fees')) {
            Schema::rename('tcp_additional_fees', 'additional_fees');
        }

        if (!Schema::hasTable('regions')) {
            Schema::rename('tcp_regions', 'regions');
        }

        if (!Schema::hasTable('sms_password_resets')) {
            Schema::rename('tcp_sms_password_resets', 'sms_password_resets');
        }

        if (!Schema::hasTable('solicitors')) {
            Schema::rename('tcp_solicitors', 'solicitors');
        }

        if (!Schema::hasTable('solicitor_offices')) {
            Schema::rename('tcp_solicitor_offices', 'solicitor_offices');
        }

        if (!Schema::hasTable('solicitor_scores')) {
            Schema::rename('tcp_solicitor_scores', 'solicitor_scores');
        }

        if (!Schema::hasTable('solicitor_users')) {
            Schema::rename('tcp_solicitor_users', 'solicitor_users');
        }

        if (!Schema::hasTable('surveys')) {
            Schema::rename('tcp_surveys', 'surveys');
        }

        if (!Schema::hasTable('tasks')) {
            Schema::rename('tcp_tasks', 'tasks');
        }

        if (!Schema::hasTable('tasks_archive')) {
            Schema::rename('tcp_tasks_archive', 'tasks_archive');
        }

        if (!Schema::hasTable('tm_milestones')) {
            Schema::rename('tcp_tm_milestones', 'tm_milestones');
        }

        if (!Schema::hasTable('users')) {
            Schema::rename('tcp_users', 'users');
        }

        if (!Schema::hasTable('user_addresses')) {
            Schema::rename('tcp_user_addresses', 'user_addresses');
        }

        if (!Schema::hasTable('user_agents')) {
            Schema::rename('tcp_user_agents', 'user_agents');
        }

        if (!Schema::hasTable('user_customers')) {
            Schema::rename('tcp_user_customers', 'user_customers');
        }

        if (!Schema::hasTable('user_permissions')) {
            Schema::rename('tcp_user_permissions', 'user_permissions');
        }

        if (!Schema::hasTable('user_roles')) {
            Schema::rename('tcp_user_roles', 'user_roles');
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('addresses', 'tcp_addresses');
        Schema::rename('agencies', 'tcp_agencies');
        Schema::rename('agency_branches', 'tcp_agency_branches');
        Schema::rename('audits', 'tcp_audits');
        Schema::rename('caches', 'tcp_caches');
        Schema::rename('cases', 'tcp_cases');
        Schema::rename('cases_history', 'tcp_cases_history');
        Schema::rename('case_disbursements', 'tcp_case_disbursements');
        Schema::rename('case_fees', 'tcp_case_fees');
        Schema::rename('disbursements', 'tcp_disbursements');
        Schema::rename('disbursements_solicitors', 'tcp_disbursements_solicitors');
        Schema::rename('documents', 'tcp_documents');
        Schema::rename('features', 'tcp_features');
        Schema::rename('feature_users', 'tcp_feature_users');
        Schema::rename('feedback_completions', 'tcp_feedback_completions');
        Schema::rename('fee_structures', 'tcp_fee_structures');
        Schema::rename('instructions', 'tcp_instructions');
        Schema::rename('instruction_documents', 'tcp_instruction_documents');
        Schema::rename('instruction_recipients', 'tcp_instruction_recipients');
        Schema::rename('last_case_reference', 'tcp_last_case_reference');
        Schema::rename('logins', 'tcp_logins');
        Schema::rename('marketing_requests', 'tcp_marketing_requests');
        Schema::rename('marketing_resources', 'tcp_marketing_resources');
        Schema::rename('milestones', 'tcp_milestones');
        Schema::rename('notes', 'tcp_notes');
        Schema::rename('password_resets', 'tcp_password_resets');
        Schema::rename('permissions', 'tcp_permissions');
        Schema::rename('additional_fees', 'tcp_additional_fees');
        Schema::rename('regions', 'tcp_regions');
        Schema::rename('sms_password_resets', 'tcp_sms_password_resets');
        Schema::rename('solicitors', 'tcp_solicitors');
        Schema::rename('solicitor_offices', 'tcp_solicitor_offices');
        Schema::rename('solicitor_scores', 'tcp_solicitor_scores');
        Schema::rename('solicitor_users', 'tcp_solicitor_users');
        Schema::rename('surveys', 'tcp_surveys');
        Schema::rename('tasks', 'tcp_tasks');
        Schema::rename('tasks_archive', 'tcp_tasks_archive');
        Schema::rename('tm_milestones', 'tcp_tm_milestones');
        Schema::rename('users', 'tcp_users');
        Schema::rename('user_addresses', 'tcp_user_addresses');
        Schema::rename('user_agents', 'tcp_user_agents');
        Schema::rename('user_customers', 'tcp_user_customers');
        Schema::rename('user_permissions', 'tcp_user_permissions');
        Schema::rename('user_roles', 'tcp_user_roles');
    }
}
