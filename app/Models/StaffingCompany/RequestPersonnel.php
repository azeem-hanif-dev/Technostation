<?php

namespace App\Models\StaffingCompany;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequestPersonnel extends Model
{
    protected $guarded = ['id'];

    use SoftDeletes;

    public function project(): BelongsTo
    {
        return $this->belongsTo(StaffingProject::class);
    }
    public function employeeFunction(): BelongsToMany
    {
     return $this->belongsToMany(EmployeeFunction::class,'activity_request_personnel', 'request_personnel_id', 'function_id');
    }
    public function supervisor()
    {
    return $this->belongsTo(Contact::class, 'temp_sup_id');
    }
}
