<?php
namespace App\Http\Controllers;

use App\Address;
use App\LegalFee;
use App\AdditionalFee;
use App\Solicitor;
use App\SolicitorOffice;
use App\SolicitorUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Yajra\Datatables\Datatables;
use Webpatser\Uuid\Uuid;

class SolicitorUsersController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($solicitorOfficeId)
    {
        $solicitorOffice = loadRecord(new SolicitorOffice, $solicitorOfficeId);

        $solicitor = $solicitorOffice->solicitor;
        $solicitorAddress = $solicitorOffice->address;

        $data = [
            'solicitor' => $solicitor,
            'solicitorOffice' => $solicitorOffice,
            'solicitorAddress' => $solicitorAddress
        ];

        return view('solicitor.office.user.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $solicitorOfficeId)
    {
        $user = Auth::user();
        $solicitorOffice = loadRecord(new SolicitorOffice, $solicitorOfficeId);

        $length_submit = count($request->title);

        for ($i = 0; $i < $length_submit; $i++) {
            $solicitorUser = new SolicitorUser();
            $solicitorUser->slug = Uuid::generate(4)->string;
            $solicitorUser->title = $request->title[$i];
            $solicitorUser->forenames = $request->forenames[$i];
            $solicitorUser->surname = $request->surname[$i];
            $solicitorUser->phone = $request->phone[$i];
            $solicitorUser->phone_other = $request->phone_other[$i];
            $solicitorUser->email = $request->email[$i];
            $solicitorUser->solicitor_id = $solicitorOffice->solicitor_id;
            $solicitorUser->solicitor_office_id = $solicitorOffice->id;
            $solicitorUser->save();
        }
        // Redirect
        if ($solicitorOffice->status === 'Pending' && !$solicitorOffice->additionalFees) {
            return redirect('/solicitors/office/' . $solicitorOffice->slug . '/additional-fees/create/')
                ->with('message', 'Solicitor Office User(s) Added');
        } else {
            return redirect('/solicitors/office/' . $solicitorOffice->slug)
                ->with('message', 'Solicitor Office User(s) Added');
        }
    }

    public function checkEmail(Request $request)
    {
        $count = 1;

        if (!empty($request->emails)) {
            $errors = [];
            foreach ($request->emails as $email) {
                if (!empty($email)) {
                    $sUsersCount = SolicitorUser::where('email', $email)->count();
                    if ($sUsersCount > 0) {
                        $errors[$count] = 'Email address is already in use. Please enter another';
                    }
                    $count++;
                }
            }
            if (!empty($errors)) {
                return response()->json([
                    'success' => false,
                    'error' => $errors,
                ]);
            }
        }

        return response()->json([
            'success' => true,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($solicitorUserId)
    {
        $solicitorUser = loadRecord(new SolicitorUser, $solicitorUserId);

        $data = [
            'suser' => $solicitorUser,
        ];
        return view('solicitor.office.user.edit-staff', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $solicitorUser = loadRecord(new SolicitorUser, $request->solicitorUser);
        $request->validate([
            'title' => 'required',
            'forenames' => 'required',
            'surname' => 'required',
            'phone' => 'required',
            'email' => 'required|unique:solicitor_users,email,' . $solicitorUser->id,
        ]);

        $solicitorUser->title = $request->title;
        $solicitorUser->forenames = $request->forenames;
        $solicitorUser->surname = $request->surname;
        $solicitorUser->phone = $request->phone;
        $solicitorUser->phone_other = $request->phone_other;
        $solicitorUser->email = $request->email;
        $solicitorUser->save();

        return redirect('/solicitors/office/' . $solicitorUser->solicitorOffice->slug)
            ->with('message', 'Solicitor User Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getUsersForOffice($solicitorOfficeId)
    {
        $solicitorOffice = loadRecord(new SolicitorOffice, $solicitorOfficeId);

        /** @var $user User */
        $user = Auth::user();
        $solicitorUserModel = new SolicitorUser();
        $users = $solicitorUserModel->users($solicitorOffice->id);
        return Datatables::of($users)->make(true);
    }
}
