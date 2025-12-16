<?php

namespace App\Models\StaffingCompany;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeFunction extends Model
{
    protected $table = 'employee_functions';

    use SoftDeletes;

    public function personnels(): BelongsToMany
    {
        return $this->belongsToMany(Personnel::class);
    }

    public function requestPersonnels() {
        // return $this->hasMany(RequestPersonnel::class);
        return $this->belongsToMany(RequestPersonnel::class, 'activity_request_personnel', 'function_id', 'request_personnel_id');

    }
}
