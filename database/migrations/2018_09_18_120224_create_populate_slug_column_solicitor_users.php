<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\SolicitorUser;
use Webpatser\Uuid\Uuid;

class CreatePopulateSlugColumnSolicitorUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('solicitor_users', 'slug')) return;
        DB::statement("ALTER TABLE `solicitor_users` ADD COLUMN `slug` CHAR(36) NOT NULL AFTER `id`;");

        $solicitorUsers = SolicitorUser::all();
        foreach($solicitorUsers as $solicitorUser) {
            $solicitorUser->slug = Uuid::generate(4)->string;
            $solicitorUser->save();
        }

        DB::statement("ALTER TABLE `solicitor_users` ADD UNIQUE INDEX `slug` (`slug`)");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('solicitor_users', 'slug'))
        {
            Schema::table('solicitor_users', function (Blueprint $table)
            {
                $table->dropColumn('slug');
            });
        }
    }
}
