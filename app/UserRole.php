<?php

namespace App;

use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

//use User;

class UserRole extends Model implements AuditableContract
{
    use Auditable;

    public function user()
    {
        return $this->hasMany('App\User');
    }

    public function getUserRoles()
    {
        return $this->all();
    }

    public function getUserRoleOfUserBeingCreated($checkUserCreated, $activeStatus)
    {
        $userRole = $this->where([
            'title' => $checkUserCreated,
            'active' => $activeStatus
        ])
            ->first();

        return $userRole;
    }

    public function validation($request, $field, $value)
    {
        if ($field === 'title') {
            $request->validate([

                'title' => 'required'

            ]);
        } elseif ($field === 'super_user') {
            $request->validate([

                'super_user' => 'required'

            ]);
        } elseif ($field === 'active') {
            $request->validate([

                'active' => 'required'

            ]);
        } elseif ($field === 'base_permissions') {
            $request->validate([

                'base_permissions' => 'required'

            ]);
        }

        return $value;
    }
}
