<?php

namespace App\Models\StaffingCompany;

use App\Models\User;
use App\Models\Customer;
use App\Models\StaffingCompany\Contact;
use Illuminate\Database\Eloquent\Model;
use App\Models\StaffingCompany\Department;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\StaffingCompany\StaffingProject;


class CampMaintenanceProject extends Model
{
    use SoftDeletes;

    protected $table = 'camp_maintenances_projects';
    protected $guarded = [];

    public function project()
    {
        return $this->belongsTo(StaffingProject::class, 'project_id');
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function supervisor()
    {
        return $this->belongsTo(Contact::class, 'supervisor_id');
    }

    public function weekStates()
    {
        return $this->hasMany(CampMaintenanceWeekState::class, 'camp_project_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function documents()
    {
        return $this->hasMany(CampProjectDocument::class, 'camp_project_id');
    }
}