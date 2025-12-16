<?php

namespace App\Models\StaffingCompany;

use App\Models\EmployAgency;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmploymentAgencyDocument extends Model
{
    protected $guarded = ['id'];

    public function employAgency(): BelongsTo
    {
        return $this->belongsTo(EmployAgency::class);
    }
}
