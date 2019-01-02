<?php

namespace App\ViewModels\Cases;

use App\Agency;
use App\AgencyBranch;
use App\User;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;
use Spatie\ViewModels\ViewModel;

class NewCase extends ViewModel
{
    public function agents()
    {
        return Agency::orderBy('name')->pluck('name', 'id');
    }

    public function branches()
    {
        return Agency::with([
            'branches' => function (HasMany $q) {
                $q->orderBy('name')->select(['id', 'agency_id', 'name']);
            }])
            ->orderBy('name')
            ->get(['name', 'id'])
            ->keyBy('id');
    }

    public function agencyStaff()
    {
        $agencies = Agency::has('staff') // Only select agencies that have staff
            ->with(['staff' => function (Relation $query) {
                $query->orderBy('surname')
                    ->select(['users.id', 'forenames', 'surname']);
            }])
            // Order the agencies by name
            ->orderBy('name')
            // Needs to get the id to link the users to the agency
            ->get(['name', 'id']);

        return $agencies->pluck('staff', 'name')
            ->map(function (Collection $staff) {
                return $staff->keyBy('id')
                    ->map(function (User $user) {
                        return (object)$user->toArray();
                    })
                    ->toArray();
            })
            ->toArray();
    }
}
