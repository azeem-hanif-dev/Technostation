<?php

namespace App\Models\StaffingCompany;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PersonnelDocument extends Model
{
    protected $guarded = ['id'];

    public function personnel(): BelongsTo
    {
        return $this->belongsTo(Personnel::class,'personnel_id');
    }
}
