<?php

namespace App\Http\Controllers;

use App\TargetsInternalStaff;
use App\User;
use DateTime;
use Faker\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class FakeUserController extends Controller
{
    public function index(Request $request)
    {
        return view('fake-users.index', ['role' => $request->query('role')]);
    }

    public function activate(Request $request)
    {
        $this->validate($request, [
            'user' => 'required|exists:users,id',
        ]);

        $this->login(User::find($request->get('user')));

        return new RedirectResponse('/');
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'role' => 'required|exists:user_roles,id',
            'password' => 'required|min:1|confirmed',
            'email' => 'required|email',
            'username' => 'required|unique:users,username',
        ]);

        $fields = ['title', 'forenames', 'surname', 'email', 'username', 'phone'];

        try {
            \DB::beginTransaction();
            $user = new User();
            foreach ($request->all($fields) as $item => $value) {
                $user->$item = $value;
            }
            $user->password = bcrypt($request->get('password'));
            $user->user_role_id = $request->get('role');

            $user->saveOrFail();

            $targets = new TargetsInternalStaff();
            $targets->user_id = $user->id;
            $targets->date_from = strtotime('midnight first day of this month');
            $targets->target = 5;
            $targets->saveOrFail();
            \DB::commit();
        } catch (\Throwable $e) {
            \DB::rollback();

            return redirect()->back()->with('error', 'Something went wrong.');
        }

        return redirect()->route('fake-users')->with('success', 'User created');
    }

    public function login(User $user)
    {
        \Auth::logout();
        \Auth::login($user);
    }
}
