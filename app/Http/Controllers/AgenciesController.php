<?php

namespace App\Http\Controllers;

use App\User;
use App\UserAgent;
use App\UserRole;
use App\Agency;
use App\AgencyBranch;
use App\UserPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use App\Notifications\AgencyUserAccountCreatedEmail;

class AgenciesController extends Controller
{

    public function __construct()
    {
        //$this->middleware('auth')->except(['index']);
        $this->middleware('auth:agency');
        //$this->middleware('auth')->except(['index']);
        //$this->middleware('auth:agency')->except(['createpassword']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userModel = new User;
        $user =
            $userModel
                ->findRelationship('tcp_user_agents', Auth::id());

        $data = [
            'user' => $user
        ];

        // load the correct dashboard depending on the user role of the user logging in.
        $dashboard = loadCorrectContent(Auth::id());

        $view = checkIfUserCanAccessPage() ? 'dashboards.' . $dashboard : config('app.permission_not_granted');

        return view($view, $data);
    }

    private function validateSubmit($agency, $request)
    {
        $this->validation();

        $requestFields = [
            'name' => request('name'),
            'active' => request('active')
        ];

        if ($agency == 'create') {
            Agency::create($requestFields);
        } else { // updating record
            $agency->update($requestFields);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validateSubmit('create', $request);
        // Redirect
        return redirect('/agencies')->with('success', 'Agency created');
    }

    /**
     * Display the specified resource.
     */
    public function show(Agency $agency)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Agency $agency)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Agency $agency, Request $request)
    {
        $this->validateSubmit($agency, $request);
        return redirect('/agencies')->with('success', 'Agency updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Agency $agency)
    {
        $agency->delete();
        return redirect('/agencies')->with('success', 'Agency deleted');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function details($id)
    {
        $agency_id = Crypt::decrypt($id);
        $agency = Agency::find($agency_id);

        //$branches = Agency::find($agency_id)->branches;
        //$branches = Agency::find($agency_id)->branches()->get();

        $branches = Agency::find($agency_id)->branches->where('active', 1);
        //$branches = Agency::find($agency_id)->branches()->where('active', 1)->get();

        $data = array(
            'agency' => $agency,
            'branches' => $branches
        );

        return view('admin.agencies.details', $data);
    }
}
