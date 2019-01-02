<?php

namespace App\Http\Controllers;

use App\Mail\ContactUs;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;

class ContactUsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $branch = $user->agencyUser->agencyBranch->name;

        $data = [
            'user' => $user,
            'branch' => $branch,
        ];
        return view('agent.contactus', $data);
    }

    public function store(Request $request)
    {
        Mail::to($request->recipient)->send(new ContactUs($request));
        return redirect('/contact-us/')->with('message', 'Request Training Email Sent');
    }
}
