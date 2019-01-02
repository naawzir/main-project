<?php

namespace App;

use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Cache extends Model implements AuditableContract
{
    use Auditable;

    public function findByKey($key)
    {
        $cache = new self;
        $caches =
            $cache
                ->where([
                    'ckey' => $key
                ])->get();

        if (!$caches->isEmpty()) {
            return $caches[0];
        }

        return false;
    }

    public function store($key, $val)
    {
        $cache = new self;

        return $cache->create([

            'ckey' => $key,
            'cval' => $val

        ]);
    }
}
