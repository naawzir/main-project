<?php

namespace App;

use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use DB;
use Auth;

class Note extends Model implements AuditableContract
{
    use Auditable;

    public function getBranchNotes($branchId)
    {
        $branchNotes = DB::table('notes as n')
            ->join('users as u', 'u.id', '=', 'n.user_id')
            ->select([
                DB::raw("n.id as `note_id`"),
                DB::raw("CONCAT(u.title, ' ', u.forenames, ' ', u.surname) as `Staff`"),
                DB::raw("DATE_FORMAT(FROM_UNIXTIME(n.date_created), '%d/%m/%Y') as `date_created`"),
                DB::raw("n.date_created as `date_created_raw`"),
                DB::raw("n.note_content")
            ])
            ->where([
                ['n.target_id', '=', $branchId],
                ['n.target', '=', 'agency-branch']
            ])
            ->orderBy('n.date_created', 'desc')
            ->get();

        return $branchNotes;
    }

    public function addBranchContactNote($branchId, $note)
    {
        $this->target_id = $branchId;
        $this->target = 'agency-branch';
        $this->target_type = 'branch-manual-note';
        $this->subtype = 'user-contact';
        $this->user_id = Auth::user()->id;
        $this->note_content = $note;

        if ($this->save()) {
            return true;
        }

        return false;
    }
}
