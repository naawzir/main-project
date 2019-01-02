<?php

namespace App;

use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use App\UserPermission;

class Permission extends Model implements AuditableContract
{
    use Auditable;

    public function getPermissions($permissionIds, $userId)
    {
        $ids = explode(',', $permissionIds);

        $permissions = $this->whereIn('id', $ids)->get();
        $countAdd = count($permissions);
        $countAdded = 0;

        foreach ($permissions as $permission) {
            $data = [
                'user_id' => $userId,
                'permission_id' => $permission->id,

            ];

            UserPermission::create($data);
            $countAdded++;
        }

        if ($countAdd !== $countAdded) {
            /*
             * I don't know what to do here yet.
             * Show a mesage which informs the user that not all permissions have been added?
             */
        }

        return true;
    }
}
