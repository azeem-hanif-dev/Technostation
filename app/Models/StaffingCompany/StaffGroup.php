<?php

namespace App\Models\StaffingCompany;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffGroup extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    public function employeeProjects() {
        return $this->hasMany(EmployeeProjectPlanning::class);
    }
}
