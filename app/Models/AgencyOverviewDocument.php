<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AgencyOverviewDocument extends Model
{
    protected $guarded = ['id'];

    public function agencyOverview(): BelongsTo
    {
        return $this->belongsTo(EmploymentAgencyOverview::class);
    }
}
