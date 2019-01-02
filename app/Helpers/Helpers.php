<?php

use App\User;
use App\UserPermission;
use App\UserRole;
use App\Permission;
use App\TargetsAgencyBranch;
use Carbon\Carbon;

/*
 * Global Functions
 * All functions in this file are callable from across the app
 */

 // public function fullname()
    // {
    //     $name = [];

    //     if (!empty($this->title)) {
    //         $name[] = $this->title;
    //     }

    //     if (!empty($this->forenames)) {
    //         $name[] = $this->forenames;
    //     }

    //     if (!empty($this->surname)) {
    //         $name[] = $this->surname;
    //     }

    //     return $fullName = implode(" ", $name);
    // }

/*
 * Check Input Function. Checks whether a User has input an Email Address or Username
 */


function checkInput($request)
{

    $login_type = filter_var($request->input('login'), FILTER_VALIDATE_EMAIL)
        ? 'email'
        : 'username';

    $request->merge([
        $login_type => $request->input('login')
    ]);

    return $login_type;
}


/*
 * Check User Access Function.
 */
function checkIfUserCanAccessPage()
{
    $permissionGranted = false;

    $request = Request::path();
    $path = $request["path"];
    $currentUrlString = rtrim(str_replace(Request::server('SCRIPT_NAME'), '', $path), '/');

    $getUserPermissions = session()->get('user_permissions');

    if (in_array($currentUrlString, $getUserPermissions)) {
        $permissionGranted = true;
    }

    return $permissionGranted;
}

/*
 * Add User Permissions Function.
 */
function addUserPermissions($userRoleId, $userId)
{
    $basePermissions = UserRole::find($userRoleId)->base_permissions;
    $permission = new Permission;
    $permission->getPermissions($basePermissions, $userId);

    return true;
}

function updatePermissionsForUser($request, $user)
{
    // get the permissions which the user has BEFORE the form is submitted
    $userPermissionModel = new UserPermission;
    $userpermissions =
        $userPermissionModel->where([
            'user_id' => $user->id
        ])->get();

    // store the permission IDs for the user in an array
    $permissionids = [];
    foreach ($userpermissions as $userpermission) {
        $permissionids[] = $userpermission->permission_id;
    }

    // check if the permissions $_POST array is empty and if it is make it an empty array
    if (!empty($request['permissions'])) {
        $permissions = $request['permissions'];
    } else {
        $permissions = [];
    }

    // delete permissions which were in tcp_user_permissions but have not been selected
    foreach ($permissionids as $userpermissionId) {
        if (!in_array($userpermissionId, $permissions)) {
            $userPermissionModel->where([
                'permission_id' => $userpermissionId,
                'user_id' => $user->id
            ])->delete();
        }
    }

    // add permissions which were not in tcp_user_permissions but have been selected
    foreach ($permissions as $permission) {
        if (!in_array($permission, $permissionids)) {
            $userPermissionModel->create([
                'permission_id' => $permission,
                'user_id' => $user->id
            ]);
        }
    }
}

function isActive($model)
{
    return (int) $model->active === 1 ? true : false;
}

function checkIfUserCanSeeThis($slug)
{
    // if the logged in user is a Super User (Software Development) then they have permissions to everything
    //if(!empty(Auth::User()->userRole()->id === 1)) {
    //return true;
    //}

    $permissionGranted = false;

    $getUserPermissions = session()->get('user_permissions');

    if (empty($getUserPermissions)) {
        return false;
    }

    if (in_array($slug, $getUserPermissions)) {
        $permissionGranted = true;
    }

    return $permissionGranted;
}

/**
 * load the correct dashboard depending on the user role of the user logging in.
 *
 * @deprecated
 */
function loadCorrectContent($userId)
{
    $userRoleId = User::find($userId)->user_role_id;

    $content = null;

    if (!empty($userRoleId)) {
        $content = UserRole::find($userRoleId)->dashboard_title;
    }

    return $content;
}

// I still haven't finished this. I'm experimenting with it (Riz).
function getModel($model)
{
    $models = [];
   /* $models['Address'] = new Address;
    $models['Agency'] = new Agency;
    $models['AgencyBranch'] = new AgencyBranch;
    $models['AgencySolicitorPartnership'] = new AgencySolicitorPartnership;
    $models['Cache'] = new Cache;
    $models['ConveyancingCase'] = new ConveyancingCase;
    $models['User'] = new User;
    $models['UserAddress'] = new UserAddress;
    $models['UserPermission'] = new UserPermission;
    $models['UserRole'] = new UserRole;*/
    $models['TargetsAgencyBranch'] = new TargetsAgencyBranch;
    return $models[$model];
}

function workingDaysInMonth($includeBankHolidays = false)
{
    $daysInMonth = 0;

    $day_count = cal_days_in_month(CAL_GREGORIAN, date('n'), date('Y'));

    for ($i = 1; $i <= $day_count; $i++) {
        if (!in_array(date('D', mktime(12, 0, 0, date('m'), $i, date('Y'))), ['Sat', 'Sun'])) {
            $daysInMonth++;
        }
    }

    $bankHolidays = 0;

    if ($includeBankHolidays) {
        $bankHolidays = Cache::get('countBankHolidaysInCurrentMonth');
    }

    $daysInMonth = $daysInMonth - $bankHolidays;

    return $daysInMonth;
}

function currentWorkingDayInCurrentMonth($includeBankHolidays = false)
{
    $daysInMonth = 0;

    $day_count = date('d');

    for ($i = 1; $i <= $day_count; $i++) {
        if (!in_array(date('D', mktime(12, 0, 0, date('m'), $i, date('Y'))), ['Sat', 'Sun'])) {
            $daysInMonth++;
        }
    }

    $bankHolidays = 0;

    if ($includeBankHolidays) {
        $bankHolidays = Cache::get('countBankHolidaysInCurrentMonth');
    }

    $daysInMonth = $daysInMonth - $bankHolidays;

    return $daysInMonth;
}

function encrypt_decrypt($action, $string)
{
    $output = false;

    $encrypt_method = "AES-256-CBC";
    $secret_key = 'This is my secret key';
    $secret_iv = 'This is my secret iv';

    // hash
    $key = hash('sha256', $secret_key);

    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a
    // warning
    $ivv = substr(hash('sha256', $secret_iv), 0, 16);

    if ($action == 'encrypt') {
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $ivv);
        $output = base64_encode($output);
    } else {
        if ($action == 'decrypt') {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $ivv);
        }
    }

    return $output;
}

function loadRecord($model, $slug)
{
    return $model::where('slug', $slug)->first();
}

function loadModel($model, $id)
{
    return $model::where('id', $id)->first();
}

function formatDisplay($text)
{
    return ucwords(str_replace('-', ' ', $text));
}

function formatDate($date)
{
    return date_format(Carbon::createFromTimestamp($date), "d/m/Y");
}

function formatPrice($price, $useDecimal, $usePoundSymbol)
{
    $decimal = !empty($useDecimal) ? 2 : 0;
    $poundSymbol = !empty($usePoundSymbol) ? '&pound;' : '';
    return html_entity_decode($poundSymbol) . number_format($price, $decimal, '.', ',');
}
