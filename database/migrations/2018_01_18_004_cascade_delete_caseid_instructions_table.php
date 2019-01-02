<?php
/**
 * This file is part of the Laravel Auditing package.
 *
 * @author     Antério Vieira <anteriovieira@gmail.com>
 * @author     Quetzy Garcia  <quetzyg@altek.org>
 * @author     Raphael França <raphaelfrancabsb@gmail.com>
 * @copyright  2015-2017
 *
 * For the full copyright and license information,
 * please view the LICENSE.md file that was distributed
 * with this source code.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CascadeDeleteCaseidInstructionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::table('instructions', function($table)
        {
            $table->foreign('case_id', 'tcp_instructions_ibfk_1')
                ->references('id')->on('cases')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('instructions', function($table)
        {
            $table->dropForeign('tcp_instructions_ibfk_1');
        });
    }

}
