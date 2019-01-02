<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AmendUserRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_roles', function(Blueprint $table) {
            $table->text('group');
        });

        DB::statement("UPDATE `user_roles` SET `group` = 'Staff' WHERE id IN ('5','6')");
        DB::statement("UPDATE `user_roles` SET `group` = 'Agent' WHERE id IN ('7','8','9')");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_roles', function(Blueprint $table) {
            $table->dropColumn('group');
        });
    }
}
