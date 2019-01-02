<?php

namespace App;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class StaffUser extends Model
{
    // A Staff User belongs to a User
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    // A Staff User can have many Transactions
    public function transaction()
    {
        return $this->hasMany('App\Transaction');
    }

    // A Staff User can have many Tasks
    public function task()
    {
        return $this->hasMany('App\Task');
    }

    public function getCurrentUsersInRole()
    {
        /** @var $user User */
        $user = Auth::user();
        $userModel = new User; // This should extend the StaffUser in future
        $users =
            $userModel
                ->where('user_role_id', '=', $user->user_role_id)
                ->get();

        return $users;
    }

    public function getAccMans()
    {
        $userModel = new User; // This should extend the StaffUser in future
        $users =
            $userModel
                ->whereIn('user_role_id', [5, 6])
                ->get();

        return $users;
    }
}
