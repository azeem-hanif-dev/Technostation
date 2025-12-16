<?php

namespace App\Models\StaffingCompany;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffingProject extends Model
{
    protected $table = 'staffing_projects';

    use SoftDeletes;

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function projectPlannings(): BelongsToMany
    {
        return $this->belongsToMany(ProjectPlanning::class);
    }

    public function personnelRequests(): BelongsToMany
    {
        return $this->belongsToMany(RequestPersonnel::class);
    }

    public function orderContainers(): HasMany
    {
        return $this->hasMany(OrderContainer::class,'project_id','id');
    }

    public function weekStates(): BelongsToMany
    {
        return $this->belongsToMany(WeekState::class, 'week_state_staffing_project');
    }

    public function projectPerformer(): BelongsTo
    {
        return $this->belongsTo(Contact::class, 'performer');
    }

    public function projectManager(): BelongsTo
    {
        return $this->belongsTo(Contact::class, 'project_manager');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(StaffingProjectDocument::class, 'staffing_project_id');
    }
}
