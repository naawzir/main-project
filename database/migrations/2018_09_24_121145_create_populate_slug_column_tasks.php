<?php

use App\Task;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePopulateSlugColumnTasks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('tasks', 'slug')) return;
        DB::statement("ALTER TABLE `tasks` ADD COLUMN `slug` CHAR(36) NOT NULL AFTER `id`;");

        $records = Task::all();
        foreach($records as $record) {
            $record->slug = Uuid::generate(4)->string;
            $record->save();
        }

        DB::statement("ALTER TABLE `tasks` ADD UNIQUE INDEX `slug` (`slug`)");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('tasks', 'slug'))
        {
            Schema::table('tasks', function (Blueprint $table)
            {
                $table->dropColumn('slug');
            });
        }
    }
}
