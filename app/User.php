<?php

namespace App;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable //implements AuditableContract
{
    const CREATED_AT = 'date_created';
    const UPDATED_AT = 'date_updated';

    public function userRole()
    {
        return $this->belongsTo('App\UserRole', 'user_role_id');
    }

    public function agencyUser()
    {
        return $this->hasOne('App\AgentUser', 'user_id', 'id');
    }

//    public function agent()
//    {
//        return $this->hasOne('App\AgentUser');
//    }

    public function staff()
    {
        return $this->hasOne('App\UserStaff');
    }
    public function solicitorUser()
    {
        return $this->hasOne('App\SolicitorUser', 'user_id');
    }

    public function getUsersByRole($userRoleID)
    {
        $users =
            $this
            ->whereIn('user_role_id', $userRoleID)
            ->get();

        return $users;
    }

    public function authenticationMethod($userRoleID)
    {
        // are you a solicitor?
        if ($userRoleID === 11) {
            return; // Auth::user();
        }
        //okay well then!
        return; // Auth::Api
    }

    public function localAccess($user)
    {
        // if the user is allowed to use TCP
        if ($user->active != 1) {
            return redirect('http://isg.markeplace'); // or something like this!?
        }
    }
}
