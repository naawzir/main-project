<?php

namespace App;

use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class SolicitorUser extends Model implements AuditableContract
{
    use Auditable;

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function solicitorOffice()
    {
        return $this->belongsTo(SolicitorOffice::class);
    }

    public function solicitor()
    {
        return $this->hasOne(Solicitor::class, 'id', 'solicitor_id');
    }

    public function users($solicitorOfficeId)
    {
        $users =
            $this->where([
                    ['active', '=', 1],
                    ['solicitor_office_id', '=', $solicitorOfficeId],
                ])
                ->get();

        return $users;
    }

    public function fullname()
    {
        $name = [];

        if (!empty($this->title)) {
            $name[] = $this->title;
        }

        if (!empty($this->forenames)) {
            $name[] = $this->forenames;
        }

        if (!empty($this->surname)) {
            $name[] = $this->surname;
        }

        $fullname = implode(" ", $name);

        return $fullname;
    }

    public function create($request, $solicitorOffice, $i)
    {
        $this->title = $request->title[$i];
        $this->forenames = $request->forenames[$i];
        $this->surname = $request->surname[$i];
        $this->phone = $request->phone[$i];
        $this->phone_other = $request->phone_other[$i];
        $this->email = $request->email[$i];
        $this->solicitor_id = $solicitorOffice->solicitor_id;
        $this->solicitor_office_id = $solicitorOffice->id;
        $this->save();
    }
}
