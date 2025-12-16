<?php

namespace App\Models\StaffingCompany;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SCLeaveTypes extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];
}
