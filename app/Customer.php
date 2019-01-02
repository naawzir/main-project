<?php

namespace App;

use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Customer extends Model implements AuditableContract
{
    use Auditable;

    /**
     * Get all Customers with the  current system
     *
     * @return mixed
     */
    public function getCustomers()
    {
        return self::all();
    }

    // public function validationCaseUser($request, $field, $value)
    // {
    //     $id = $this->id;
    //     if ($field === 'title') {
    //         $request->validate([
    //             'title' => 'required'
    //         ]);
    //     } elseif ($field === 'forenames') {
    //         $request->validate([
    //             'forenames' => 'required'
    //         ]);
    //     } elseif ($field === 'surname') {
    //         $request->validate([
    //             'surname' => 'required'
    //         ]);
    //     } elseif ($field === 'email') {
    //         $request->validate([
    //             'email' => 'required_without_all:mobile,phone|nullable|email|unique:tcp_users,email,' . $id
    //         ]);
    //     } elseif ($field === 'phone') {
    //         $request->validate([
    //             'phone' => 'required_without_all:email,mobile|nullable|min:4|max:12',
    //         ]);
    //     } elseif ($field === 'mobile') {
    //         $request->validate([
    //             'mobile' => 'required_without_all:email,phone|nullable|regex:/^07\d{9}$/|unique:tcp_users,mobile,'
    // . $id
    //         ]);
    //     }
    // }
}
