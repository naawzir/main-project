<?php

namespace App\Http\Controllers;

use App\AgencyBranch;
use App\User;
use App\Agency;
use App\Note;
use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Yajra\Datatables\Datatables;

class AgencyBranchesController extends Controller
{

/*    public function __construct()
    {
        $this->middleware('auth')->except(['index']);;}*/
    /**
     * Remove the specified resource from storage.
     */
    public function details($id)
    {
        $branch_id = Crypt::decrypt($id);
        $branch = AgencyBranch::find($branch_id);
        $agencyBranchUsers = AgencyBranch::find($branch_id)->users;
        $users = null;
        if (count($agencyBranchUsers) > 0) {
            $userModel = new User;
            $users = $userModel->users($agencyBranchUsers);
        }

        $data = array(
            'branch' => $branch,
            'users' => $users ? $users : []
        );

        return view('admin.pagedesign.branches.details', $data);
    }

    public function edituser($id)
    {
        $user_id = Crypt::decrypt($id);
        $user = User::find($user_id);
        $userAgencyBranch = $user->user;

        $agencyModel = new Agency;
        $activeAgencies = $agencyModel->activeAgencies();

        $agencyBranchModel = new AgencyBranch;
        $activeBranches = $agencyBranchModel
            ->where('active', 1)
            ->orderBy('name')
            ->get();

        $data = array(
            'user' => $user,
            'userAgencyBranch' => $userAgencyBranch,
            'activeAgencies' => $activeAgencies,
            'activeBranches' => $activeBranches

        );

        return view('admin.pagedesign.branches.edituser', $data);
    }

    public function branchNotesRecords(AgencyBranch $branch)
    {
        /** @var $user User */
        $user = Auth::user();

        $noteModel = new Note;
        $branchNotes = $noteModel->getBranchNotes($branch->id);
        return Datatables::of($branchNotes)->make(true);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addBranchContactNote(Request $request)
    {
        $branchId = $request['branchId'];
        $sendEmail = !empty($request['sendEmail']) ? true : false;

        $note = $request['note'];
        $noteModel = new Note;
        $note = $noteModel->addBranchContactNote($branchId, $note);

        if (!empty($note)) {
            if ($sendEmail) {
                // for now, let's use my work email address to see if it actually sends the email
                $email = "rizwaan.khan@intelligentservicesgroup.com";

                $title = "abc";

                //$user = new User;
                //$user->notify(new AgencyBranchContact($title));
            }
            return response()->json([
                "success" => true
            ]);
        }
    }
}
