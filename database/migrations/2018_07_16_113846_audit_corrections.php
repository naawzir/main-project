<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AuditCorrections extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    if (Schema::hasTable('audits')) return;
        Schema::table('audits', function (Blueprint $table) {
            $table->string('event')->change();
            $table->unsignedInteger('auditable_id')->length(10)->change();
            $table->string('auditable_type')->change();
            $table->dropIndex('idx_tcp_audits__auditable_id');
            $table->dropIndex('idx_tcp_audits__user_id');
            $table->index(['user_id', 'user_type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('audits', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'user_type']);
            $table->index('user_id', 'idx_tcp_audits__user_id');
            $table->index('auditable_id', 'idx_tcp_audits__auditable_id');
            $table->string('auditable_type')->default(null)->change();
            $table->unsignedInteger('auditable_id')->length(10)->default(null)->change();
            $table->string('event')->default(null)->change();
        });
    }
}
