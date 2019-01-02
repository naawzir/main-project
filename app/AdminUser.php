<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdminUser extends Model
{
    // An Admin User Belongs to User
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    // An Admin User can Authenticate
    // public function authenticate()
    // {
    // }
}
