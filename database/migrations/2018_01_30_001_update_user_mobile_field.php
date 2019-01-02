<?php

use App\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUserMobileField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            
            $table->string('mobile')->length(64)->after('hashsalt')->nullable();

        });

        foreach (User::all() as $user) {
            
            $phone = preg_replace('#\s+#', '', $user->phone); // remove spaces
            $phone = preg_replace('#[a-zA-Z]+#', '', $phone); // remove text
            $other = preg_replace('#\s+#', '', $user->phone_other); // remove spaces
            $other = preg_replace('#[a-zA-Z]+#', '', $other); // remove text
            $mobile = null;

            if(substr($phone,0,2) == '07') {

                $mobile = $phone;

            } elseif (substr($other,0,2) == '07') {

                $mobile = $other;
            
            }

            $user->update([
                'mobile' => $mobile,
                'phone' => $phone,
                'phone_other' => $other
            ]);

        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
