<?php declare(strict_types=1);

namespace App\Dashboard;

use App\User;
use App\Cases;
use App\SolicitorOffice;
use App\Solicitor as Sol;
use Illuminate\Support\Facades\Auth;
use App\FeedbackCustomerForTcp;
use App\FeedbackAgentForTcp;

class Solicitor implements Dashboard
{
    public function getData(User $user): array
    {
        $solicitorUser = $user->solicitorUser;
        $solicitorOffices = new SolicitorOffice;
        $solicitor = new Sol;
        $mySolicitors = $solicitor->getMySolicitors();
        $solicitorOffices = $solicitorOffices
            ->where([
                ['solicitor_id', '=', $solicitorUser->solicitor_id],
                ['status', '=', 'Active']
            ])->get();
        
        $data = [
            'solicitorOffices' => $solicitorOffices,
            'mySolicitors' => $mySolicitors
        ];

        return $data;
    }
}
