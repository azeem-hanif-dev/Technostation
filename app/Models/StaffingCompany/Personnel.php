<?php

namespace App\Models\StaffingCompany;

use App\Models\EmployAgency;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;


class Personnel extends Model
{
    use Notifiable;
    protected $guarded = 'id';

    protected $with = ['staffType'];

    protected $hidden = [
        'password',
    ];

    use SoftDeletes;

    public function agency(): BelongsTo
    {
        return $this->belongsTo(EmployAgency::class, 'employ_agency_id');
    }

    public function planning(): HasOne
    {
        return $this->hasOne(EmployeeProjectPlanning::class, 'employee_id');
    }

    public function staffType(): BelongsTo
    {
        return $this->belongsTo(StaffType::class, 'staff_type_id');
    }

    public function weekCards(): HasMany
    {
        return $this->hasMany(SfWeekCard::class);
    }

    public function employeeFunction(): BelongsToMany
    {
        return $this->belongsToMany(EmployeeFunction::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(PersonnelDocument::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function timeCards()
    {
        return $this->hasMany(SCTimeCard::class, 'personnel_id');
    }
}
