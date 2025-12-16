<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmploymentAgencyOverview extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    public function documents(): HasMany
    {
        return $this->hasMany(AgencyOverviewDocument::class);
    }

    public function employagency()
    {
        return $this->belongsTo(EmployAgency::class, 'employ_agency_id');
    }
}
