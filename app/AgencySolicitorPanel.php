<?php

namespace App;

use Auth;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class AgencySolicitorPanel extends Model implements AuditableContract
{
    protected $table = 'agency_solicitor_panel';

    use Auditable;

    public function agencySolicitorOfficePartnership($agencyId, $solOfficeId)
    {
        $partnership = $this->where([
            ['agency_id', '=', $agencyId],
            ['solicitor_office_id', '=', $solOfficeId],
        ])->first();

        return $partnership;
    }

    public function solicitorOfficesAssignedToAgency()
    {
        $user = Auth::User();
        $agencyId = $user->agencyUser->agency_id;
        $solicitorOfficeIds = null;
        if (!empty($agencyId)) {
            $solicitorOffices = $this->where('agency_id', $agencyId)->get();
            $solicitorOfficeIds = $solicitorOffices->pluck('solicitor_office_id')->toArray();
        }
       
        return $solicitorOfficeIds;
    }
}
