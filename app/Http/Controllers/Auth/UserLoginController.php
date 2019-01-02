<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\PostcodeLookup\PostcodeLookup;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class UserLoginController extends Controller
{

    use AuthenticatesUsers;

     /**
     * Where to redirect users after login.
     *
     * @var string
     */
    
    protected $redirectTo = '/';

    /**
    *
    * Create a new authentication controller instance.
    *
    * @return void
    *
    */

    public function __construct()
    {

        $this->middleware('auth')->only('destroy');
    }

    public function create()
    {
        return view('auth.login');
    }

    public function username()
    {

        return 'username';
    }


    public function login(Request $request)
    {
        /*
         * The first step here is to get the values that have been entered in
         * to the Login field and the Password field and authenticate them.
         */
        $request->validate([
            'login' => 'required',
            'password' => 'required|min:8',
        ]);

        // Next, we check whether the input is an Email or a Username input
        $login_type = checkInput($request);

        // we check that the username and password are correct but we do not log them in yet
        if (Auth::validate($request->only($login_type, 'password'))) {
            $user = Auth::getLastAttempted();

            // we check that the user is not active
            if (!isActive($user)) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors([
                        'login' => 'Your account is not active',
                    ]);
            } else {
                // the username and password are correct and the user is active so we log them in
                Auth::login($user);

                // the user will be redirected to their original destination
                return redirect()->intended('');
            }
        } else {
            // the username and/or password are not correct
            return redirect()->back()
                ->withInput()
                ->withErrors([
                    'login' => 'Invalid Login Credentials Provided. Please Try Again.',
                ]);
        }
    }

    public function destroy()
    {

        Auth::logout();
        Session::flush();
        return redirect('/login');
    }
}
