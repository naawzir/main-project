<?php

namespace App;

use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use Auth;

class UserPermission extends Model implements AuditableContract
{
    use Auditable;

    public function getUserPermissions()
    {
        $userPermissions = $this->where('user_id', Auth::id())->get();

        $permissionIds = [];
        foreach ($userPermissions as $userPermission) {
            $permissionIds[] = $userPermission->permission_id;
        }

        $permissions = [];
        foreach ($permissionIds as $permissionId) {
            $permissions[] = Permission::find($permissionId)->function;
        }

        return $permissions;
    }
}
