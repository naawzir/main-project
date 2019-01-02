<?php

namespace App\Http\Controllers;

use App\Mail\RequestTraining;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\AgencyBranch;
use Illuminate\Support\Facades\Auth;
use App\User;

class TrainingController extends Controller
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
        return view('agent.training', $data);
    }

    public function store(Request $request)
    {
        Mail::to($request->recipient)->send(new RequestTraining($request));
        return redirect('/training/')->with('message', 'Request Training Email Sent');
    }
}
