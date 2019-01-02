<?php

namespace App\Policies;

use App\User;
use App\SolicitorOffice;
use Illuminate\Auth\Access\HandlesAuthorization;

class OfficePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the solicitor office.
     *
     * @param  \App\User  $user
     * @param  \App\SolicitorOffice  $solicitorOffice
     * @return mixed
     */
    public function view(User $user, SolicitorOffice $solicitorOffice)
    {
        //
    }

    /**
     * Determine whether the user can create solicitor offices.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the solicitor office.
     *
     * @param  \App\User  $user
     * @param  \App\SolicitorOffice  $solicitorOffice
     * @return mixed
     */
    public function update(User $user, SolicitorOffice $solicitorOffice)
    {
        //
    }

    /**
     * Determine whether the user can delete the solicitor office.
     *
     * @param  \App\User  $user
     * @param  \App\SolicitorOffice  $solicitorOffice
     * @return mixed
     */
    public function delete(User $user, SolicitorOffice $solicitorOffice)
    {
        //
    }

    /**
     * Determine whether the user can submit the solicitor office to panel manager.
     *
     * @param  \App\User  $user
     * @param  \App\SolicitorOffice  $solicitorOffice
     * @return mixed
     */
    public function submitToPM(User $user, SolicitorOffice $solicitorOffice)
    {
        return ($user->userRole->dashboard_title ?? null) === 'bdm';
    }

    /**
     * Determine whether the user can submit the solicitor office to TM.
     *
     * @param  \App\User  $user
     * @param  \App\SolicitorOffice  $solicitorOffice
     * @return mixed
     */
    public function submitToTM(User $user, SolicitorOffice $solicitorOffice)
    {
        return ($user->userRole->dashboard_title ?? null) === 'panel-manager';
    }

    /**
     * Determine whether the user can set the solicitor office to Active.
     *
     * @param  \App\User  $user
     * @param  \App\SolicitorOffice  $solicitorOffice
     * @return mixed
     */
    public function submitToMarketplace(User $user, SolicitorOffice $solicitorOffice)
    {
        return ($user->userRole->dashboard_title ?? null) === 'panel-manager';
    }
}
