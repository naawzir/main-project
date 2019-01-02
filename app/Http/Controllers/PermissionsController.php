<?php

namespace App\Http\Controllers;

use Hash;
use App\User;
use App\Message;
use App\UserRole;
use App\Permission;
use App\UserPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Auth;

class PermissionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $permissions = Permission::all();

        $data = [
            'records' => $permissions
        ];

        return view('/', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('/');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'function' => 'required',
        ]);

        Permission::create([

            'name' => $request['name'],
            'function' => $request['function'],
            'active' => $request['active'],

        ]);

        // Redirect
        return redirect('/')->with('message', 'Permission created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $permissionId = Crypt::decrypt($id);
        $permission = Permission::find($permissionId);

        $data = [
            'userLoggedIn' => Auth::User(),
            'permission' => $permission,
        ];

        return view('/', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $permissionId = Crypt::decrypt($id);
        $permission = Permission::find($permissionId);

        $request->validate([
            'name' => 'required',
            'function' => 'required',
        ]);

        $permission->update([

            'name' => $request['name'],
            'function' => $request['function'],
            'active' => $request['active'],

        ]);

        // Redirect
        return redirect('/')->with('message', 'Permission updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Permission $permission)
    {
        $permission->delete();
        return redirect('/')->with('message', 'Permission has been deleted');
    }
}
