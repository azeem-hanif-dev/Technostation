<?php

namespace App\Models\StaffingCompany;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffType extends Model
{
    use SoftDeletes;

    public function staff(): HasOne
    {
        return $this->hasOne(Personnel::class, 'staff_type_id');
    }
}
