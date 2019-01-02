<?php

namespace App;

use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Document extends Model implements AuditableContract
{
    use Auditable;

    public function getDocumentsForCase($case)
    {
        $documents =
            self::select([
                'id',
                'slug',
                'file_name',
                'notes',
                'title'
            ])->where([

                ['target_type', '=', 'case'],
                ['target_id', '=', $case->id]

            ])->get();

        if (!$documents) {
            return response()->json([
                'success' => false,
                'error' => 'Could not find associated Documents!'
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'documents' => $documents
            ]
        ]);
    }
}
