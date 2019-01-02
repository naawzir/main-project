<?php

namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Model extends Eloquent
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    const CREATED_AT = 'date_created';
    const UPDATED_AT = 'date_updated';

    protected $dateFormat = "U";

    public function lastRecord($model)
    {
        return $model->orderBy('date_created', 'desc')->first();
    }
}
