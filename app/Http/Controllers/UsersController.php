<?php
namespace App\Http\Controllers;

use Hash;
use App\User;
use App\Address;
use App\ConveyancingCase;
use App\Customer;
use App\TransactionCustomer;
use App\Message;
use App\UserRole;
use App\Permission;
use App\UserPermission;
use App\notifications\UserActivationSms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Auth\PasswordBroker;
use Auth;
use Illuminate\Support\Facades\Log;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userPermissionModel = new UserPermission;

        /** @var $userLoggedIn User */
        $userLoggedIn = Auth::User();
        $user = new User;
        $userRole = $userLoggedIn->userRole()->first();

        if ($userRole->id === 1) {
            //$records = $user->all();
            $records =
                $user
                    ->whereNotIn('user_role_id', [10])
                    ->get();
        } elseif ($userRole->id === 5) {
            $records =
                $user
                    ->whereIn('user_role_id', [5, 6])
                    ->get();
        } elseif ($userRole->group === 'Agent') {
            $agencyUser = $userLoggedIn->agencyUser;

            if ($userRole === 7) { // Business Owners will be able to view all users within the Agency
                $records = $user
                    ->join('user_agents', "user_agents.user_id", '=', 'users.id')
                    ->whereIn('users.user_role_id', [7, 8, 9])
                    ->where('user_agents.agency_id', $agencyUser->agency_id)
                    ->get();
            } elseif ($userRole === 8) {
                $records = $user
                    ->join('user_agents', "user_agents.user_id", '=', 'users.id')
                    ->whereIn('users.user_role_id', [8, 9])
                    ->where('user_agents.agency_id', $agencyUser->agency_id)
                    ->where('user_agents.agency_branch_id', $agencyUser->agency_branch_id)
                    ->get();
            }
        }

        $data = [
            'records' => $records,
            'userPermission' => $userPermissionModel
        ];

        return view('/', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
/*    public function create()
    {
        $userRoles = UserRole::where('active', 1)->get();

        $data = [
            'userLoggedIn' => Auth::User(),
            'userRoles' => $userRoles,
        ];

        return view('/', $data);
    }*/

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    /*public function store(Request $request)
    {
        $request->validate([

            'user_role_id' => 'required',
            'forenames' => 'required',
            'surname' => 'required',
            'email' => 'nullable|unique:tcp_users,email',
            'mobile' => 'nullable|regex:/^(07)[0-9]{9}$/|unique:tcp_users,mobile'

        ]);

        User::create([

            'title' => ucfirst($request['title']),
            'forenames' => $request['forenames'],
            'surname' => $request['surname'],
            'email' => $request['email'],
            'username' => $request['username'],
            'mobile' => $request['mobile'],
            'phone' => $request['phone'],
            'phone_other' => $request['phone_other'],
            'user_role_id' => $request['user_role_id'],
            'active' => 0,

        ]);

        // Redirect
        return redirect('/')->with('message', 'User created');
    }*/

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    /*public function show($id)
    {
        //
    }*/

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    /*public function edit($id)
    {
        $userId = Crypt::decrypt($id);

        $user = User::find($userId);
        $user_role = $user->userRole()->first();
        $userRoles = UserRole::where('active', 1)->get();

        $adminUserRoleIds = [1, 2, 3, 4, 5, 6];
        $agencyUserRoleIds = [7, 8, 9];

        if ($user_role->group === 'Staff') {
            $userRoles = UserRole::whereIn('id', $adminUserRoleIds)->get();
        } elseif ($user_role->group === 'Agent') {
            $userRoles = UserRole::whereIn('id', $agencyUserRoleIds)->get();
        }
        /*        else {
                    $userRoles = UserRole::where('id', 10)->get();
                }*/
/*
        $userPermissionModel = new UserPermission;
        $userPermissions =
            $userPermissionModel
                ->select('permission_id')
                ->where('user_id', $userId)
                ->get();

        $permissionIds = [];
        foreach ($userPermissions as $userPermission) {
            $permissionIds[] = $userPermission->permission_id;
        }

        $permissionModel = new Permission;
        $selectedPermissions = $permissionModel
            ->whereIn('id', $permissionIds)
            ->get();

        $notPermissions = $permissionModel
            ->whereNotIn('id', $permissionIds)
            ->get();


        $data = [
            'userLoggedIn' => Auth::User(),
            'user' => $user,
            'userRoles' => $userRoles,
            'selectedPermissions' => $selectedPermissions,
            'notPermissions' => $notPermissions,
        ];

        return view('/', $data);
    }*/

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    /*public function update(Request $request, $id)
    {
        $userId = Crypt::decrypt($id);
        $user = User::find($userId);

        $request->validate([

            'forenames' => 'required',
            'surname' => 'required',
            'email' => 'nullable|unique:tcp_users,email,' . $user->id,
            'mobile' => 'nullable|regex:/^(07)[0-9]{9}$/|unique:tcp_users,mobile,' . $user->id

        ]);

        updatePermissionsForUser($request, $user);

        $user->update([

            'title' => ucfirst($request['title']),
            'forenames' => $request['forenames'],
            'surname' => $request['surname'],
            'email' => $request['email'],
            'username' => $request['username'],
            'mobile' => $request['mobile'],
            'phone' => $request['phone'],
            'phone_other' => $request['phone_other'],
            'user_role_id' => $request['user_role_id'],
            'active' => $request['active'],
            //'position' => $request['position'],

        ]);

        // Redirect
        return redirect('admin/users')->with('message', 'User updated');
    }*/

    // this gets ran as part of an Ajax request when clicking the list of user permissions
    public function permissions(Request $request)
    {
        $userId = Crypt::decrypt($request['userIdEncrypted']);
        $user = User::find($userId);

        updatePermissionsForUser($request, $user);

        die();
    }

    public function userrole(Request $request)
    {
        $userId = Crypt::decrypt($request['userIdEncrypted']);
        $user = User::find($userId);

        $userRoleId = $request['userRoleId'];

        $user->update([
            'user_role_id' => $request['userRoleId']
        ]);

        // delete all user permissions for the user then reapply them with the correct permissions
        $userPermissionModel = new UserPermission;
        $userPermissionModel->where([
            'user_id' => $user->id
        ])->delete();

        // add permissions for agency user
        $saved = addUserPermissions($userRoleId, $user->id);

        if (!empty($saved)) {
            echo "success";
        } else {
            echo "fail";
        }

        die();
    }

    public function find(Request $request)
    {
        $title = $request['title'];
        $forenames = $request['forenames'];
        $surname = $request['surname'];
        $userModel = new User;
        $users = $userModel
                ->where("title", $title)
                ->where("forenames", $forenames)
                ->where("surname", "like", $surname . "%")
                ->where("user_role_id", "10")
                ->limit(20)
                ->get();
        $html = [];

        foreach ($users as $user) {
            $customer = $user->customer;
            $addressModel = new Address;
            /** @var $address Address */
            $address = $addressModel->find($customer->address_id);

            if (!empty($address)) {
                $buildingName = $address->building_name;
                $buildingNumber = $address->building_number;
                $addressLine1 = $address->address_line_1;
                $town = $address->town;
                $county = $address->county;
                $postcode = $address->postcode;
                $addressToDisplay = $address->getAddress();
                $id = Crypt::encrypt($user->id);
                $name = $user['title'] . ' ' . $user['forenames'] . ' ' . $user['surname'];

                $html[] = "<li class='auto-suggest-client' id='$id' data-id='$user->id'><span>" . $user['title']
                    . ' ' . $user['forenames'] . ' ' . $user['surname'] . '</span><br><span>'
                    . $addressToDisplay . "</span></li>";
                $html[] = '<span class="hidden-span" id="autoTitle_' . $user->id . '">' . $user["title"] . '</span>';
                $html[] = '<span class="hidden-span" id="autoForenames_'
                    . $user->id . '">'
                    . $user["forenames"]
                    . '</span>';
                $html[] = '<span class="hidden-span" id="autoSurname_'
                    . $user->id . '">'
                    . $user["surname"]
                    . '</span>';
                $html[] = '<span class="hidden-span" id="autoEmail_' . $user->id . '">' . $user["email"] . '</span>';
                $html[] = '<span class="hidden-span" id="autoMobile_' . $user->id . '">' . $user["mobile"] . '</span>';
                $html[] = '<span class="hidden-span" id="autoPhone_' . $user->id . '">' . $user["phone"] . '</span>';
                $html[] = '<span class="hidden-span" id="autoBuildingNumber_'
                    . $user->id . '">'
                    . $buildingNumber
                    . '</span>';
                $html[] = '<span class="hidden-span" id="autoBuildingName_'
                    . $user->id
                    . '">' . $buildingName
                    . '</span>';
                $html[] = '<span class="hidden-span" id="autoAddrLine1_' . $user->id . '">' . $addressLine1 . '</span>';
                $html[] = '<span class="hidden-span" id="autoTown_' . $user->id . '">' . $town . '</span>';
                $html[] = '<span class="hidden-span" id="autoCounty_' . $user->id . '">' . $county . '</span>';
                $html[] = '<span class="hidden-span" id="autoPostcode_' . $user->id . '">' . $postcode . '</span>';
            }
        }

        return response()->json([
            "success" => true,
            "data" => [
                "html" => $html
            ]
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($user)
    {
        User::destroy($user);
        return redirect('/')->with('message', 'User has been deleted');
    }
}
