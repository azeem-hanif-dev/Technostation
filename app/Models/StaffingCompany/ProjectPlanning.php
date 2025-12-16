<?php

namespace App\Models\StaffingCompany;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectPlanning extends Model
{
    protected $guarded = ['id'];

    use SoftDeletes;

    public function employeeProjects(): HasMany
    {
        return $this->hasMany(EmployeeProjectPlanning::class);
    }

    // Only active (not deleted) employee projects
    public function employeeProjectsActive(): HasMany
    {
        return $this->hasMany(EmployeeProjectPlanning::class)
                    ->where('planning_delete', 0);
    }

    public function staffingProjects(): BelongsToMany
    {
        return $this->belongsToMany(StaffingProject::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Personnel::class);
    }

    // ---------- Updated counts ----------
    public function countActiveEmployeeProjects(): int
    {
        return $this->employeeProjectsActive()->where('status', 1)->count();
    }

    public function countInactiveEmployeeProjects(): int
    {
        return $this->employeeProjectsActive()->where('status', 2)->count();
    }

    public function countAllEmployeeProjects(): int
    {
        return $this->employeeProjectsActive()->count();
    }

    public function countActiveEmployeeProjectWise($project_id): int
    {
        return $this->employeeProjectsActive()
                    ->where('project_id', $project_id)
                    ->where('status', 1)
                    ->count();
    }

    public function countInactiveEmployeeProjectsWise($project_id): int
    {
        return $this->employeeProjectsActive()
                    ->where('project_id', $project_id)
                    ->where('status', 2)
                    ->count();
    }
}

