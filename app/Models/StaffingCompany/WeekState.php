<?php

namespace App\Models\StaffingCompany;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class WeekState extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];
    protected $with = ['weekCards', 'projects', 'documents'];

    public function weekCards(): HasMany
    {
        return $this->hasMany(SfWeekCard::class);
    }

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(StaffingProject::class, 'week_state_staffing_project');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(WeekStateDocument::class, 'week_state_id');
    }


    public function staffingProjects()
    {
        return $this->belongsToMany(
            StaffingProject::class,
            'week_state_staffing_project',
            'week_state_id',
            'staffing_project_id'
        );
    }
}
