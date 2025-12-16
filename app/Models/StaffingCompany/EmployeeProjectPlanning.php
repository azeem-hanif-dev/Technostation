<?php

namespace App\Models\StaffingCompany;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeProjectPlanning extends Model
{
    use SoftDeletes;

    protected $table = 'employee_project_plannings';

    protected $fillable = [
        'project_planning_id',
        'employee_id',
        'group_id',
        'project_id',
        'week_no',
        'geschikt',
        'status',
        'notes',
        'date',
        'planning_delete',
        'inactive'
    ];


    protected $casts = [
        // 'created_at' => 'date',
        'inactive' => 'boolean',
    ];

    public function projectPlanning(): BelongsTo
    {
        return $this->belongsTo(ProjectPlanning::class, 'project_planning_id');
    }

    public function staffGroup(): BelongsTo
    {
        return $this->belongsTo(StaffGroup::class, 'group_id');
    }

    public function personnel(): BelongsTo
    {
        return $this->belongsTo(Personnel::class, 'employee_id');
    }

    public function project()
    {
        return $this->belongsTo(StaffingProject::class, 'project_id');
    }

    public function employeeFunction()
    {
        return $this->belongsTo(EmployeeFunction::class, 'geschikt');
    }
}
