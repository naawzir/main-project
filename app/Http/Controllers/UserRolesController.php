<?php

namespace App\Http\Controllers;

use App\UserRole;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Auth\PasswordBroker;

class UserRolesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $userRoles = UserRole::all();

        $permissionModel = new Permission;

        $permissions =
            $permissionModel
                ->where('active', 1)
                ->get();

        $data = [

            'records' => $userRoles,
            'permissions' => $permissions

        ];

        return view('admin.userroles.index', $data);
    }
/*
    public function additional(Request $request)
    {
        $id = Crypt::decrypt($request['id']);
        $userRole = UserRole::find($id);

        $basePermissions = $userRole->base_permissions;
        $ids = explode(',', $basePermissions);

        $permissionModel = new Permission;
        $selectedPermissions = $permissionModel
            ->whereIn('id', $ids)
            //->where('active', 1)
            ->get();

        $notPermissions = $permissionModel
            ->whereNotIn('id', $ids)
            //->where('active', 1)
            ->get();

        return response()->json([

            "success" => true,
            "data" => [

                'selectedPermissions' => $selectedPermissions,
                'notPermissions' => $notPermissions,
            ]

        ]);
    }

    public function updateAdditional(Request $request)
    {
        $id = Crypt::decrypt($request['id']);
        $userRole = UserRole::find($id);

        $basePermissions = implode(',', $request['base_permissions']);

        $userRole
            ->update([
                'base_permissions' => $basePermissions
            ]);

        return response()->json([
            'success' => true,
        ]);
    }

    public function create(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'super_user' => 'required',
            'active' => 'required'
        ]);

        $basePermissions = implode(',', $request['base_permissions']);

        return response()->json([
            'success' => true,
        ]);
    }
*/
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
/*    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'super_user' => 'required',
            'active' => 'required'
        ]);

        UserRole::create([
            'title' => $request['title'],
            'description' => $request['description'],
            'super_user' => $request['super_user'],
            'active' => $request['active'],
        ]);

        // Redirect
        return redirect('/')->with('message', 'User role created');
    }
*/
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $userRoleId = Crypt::decrypt($id);
        $userRole = UserRole::find($userRoleId);

        $basePermissions = UserRole::find($userRoleId)->base_permissions;
        $ids = explode(',', $basePermissions);

        $permissionModel = new Permission;
        $selectedPermissions = $permissionModel
            ->whereIn('id', $ids)
            //->where('active', 1)
            ->get();

        $notPermissions = $permissionModel
            ->whereNotIn('id', $ids)
            //->where('active', 1)
            ->get();

        $data = [
            'userLoggedIn' => Auth::User(),
            'userRole' => $userRole,
            'selectedPermissions' => $selectedPermissions,
            'notPermissions' => $notPermissions,
        ];

        return view('/', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $id = Crypt::decrypt($request['id']);
        $field = $request['field'];
        $value = $request['value'];

        //echo $id . ": " . $field . ": " . $value;

        /** @var $userRole UserRole */
        $userRole = UserRole::find($id);

        $updatedValue = $userRole->validation($request, $field, $value);

        $userRole->update([

            $field => $updatedValue

        ]);

        return response()->json([

            "success" => true,
            "data" => [

                "value" => $updatedValue

            ]

        ]);
    }

    // this gets ran as part of an Ajax request when clicking the list of permissions
    public function permissions(Request $request)
    {
        $userRoleId = Crypt::decrypt($request['userRoleIdEncrypted']);
        $userRole = UserRole::find($userRoleId);

        $permissions = $request['base_permissions'];
        $permissionsToInsert = !empty($permissions) ? implode(",", $permissions) : "";

        $userRole->update([
            'base_permissions' => $permissionsToInsert
        ]);

        die();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserRole $userrole)
    {
        $userrole->delete();

        return response()->json([

            "success" => true

        ]);
    }
}
