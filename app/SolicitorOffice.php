<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

/**
 * @method static Builder|self onboarding(bool $bdm = false)
 * @property-read string $slug
 */
class SolicitorOffice extends Model implements AuditableContract
{
    use Auditable, GeneratesSlug;

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function users()
    {
        return $this->hasMany(SolicitorUser::class, 'solicitor_office_id');
    }

    public function additionalFees()
    {
        return $this->hasOne(AdditionalFee::class, 'solicitor_office_id', 'id');
    }

    public function feeStructures()
    {
        return $this->hasMany(LegalFee::class, 'solicitor_office_id');
    }

    public function solicitor()
    {
        return $this->belongsTo(Solicitor::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class, 'address_id');
    }

    public function agentFeedback()
    {
        return $this->hasMany(FeedbackAgentForSolicitorOffice::class);
    }

    public function disbursements()
    {
        return $this->hasMany(SolicitorDisbursement::class, 'solicitor_office_id', 'id');
    }

    public function activeOffices()
    {
        $offices =
            $this->where('active', 1)
                ->orderBy('name')
                ->get();

        return $offices;
    }

    public function scopeOnboarding(Builder $query, bool $bdm = false)
    {
        if ($bdm) {
            $query->whereIn('status', ['Pending'])
                ->groupBy('solicitor_id');
        } else {
            $query->whereIn('status', ['Onboarding', 'SentToTM'])
                ->groupBy('solicitor_id');
        }
    }

    public function countOnboardingOffices($bdm = false, $solicitor_id = null)
    {
        if (!empty($solicitor_id) && $bdm) {
            $officecount = self::whereIn('status', ['Pending'])
                ->where('solicitor_id', '=', $solicitor_id)
                ->count();
        } else {
            $officecount = self::whereIn('status', ['Onboarding', 'SentToTM'])
                ->where('solicitor_id', '=', $solicitor_id)
                ->count();
        }

        return $officecount;
    }

    public function canBeSubmitted($type)
    {
        if ($this->status === $type) {
            return true;
        }
        return false;
    }

    public function getAgentRating()
    {
        $record = new FeedbackAgentForSolicitorOffice();
        return $record->getAverageForSolicitorOffice($this->id);
    }

    public function getCustomerRating()
    {
        $record = new FeedbackCustomerForSolicitorOffices();
        return $record->getAverageForSolicitorOffice($this->id);
    }

    public function getFees($caseType)
    {
        $fees = $this->feeStructures()
            ->where([
                ['active', '=', 1],
                ['case_type', '=', $caseType],
            ])->get();

        return $fees;
    }
}
