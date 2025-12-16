<?php

namespace App\Models\StaffingCompany;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class SfWeekCard extends Model
{
    use SoftDeletes;
    protected $appends = ['project_id'];

    protected $guarded = ['id'];

    protected $with = ['timeCards'];

    public function weekState(): BelongsTo
    {
        return $this->belongsTo(WeekState::class, 'week_state_id');
    }

    public function personnel(): BelongsTo
    {
        return $this->belongsTo(Personnel::class, 'personnel_id');
    }

    public function timeCards():HasMany
    {
        return $this->hasMany(SCTimeCard::class);
    }


    public function GetTimeCardEmployeeByWeekID($id): array
    {
        return DB::select(DB::raw("SELECT
                  w.Weeknumber,
                    concat(e.Firstname,' ',e.Lastname) Name,
                    e.Id Employee_Id,
                    t.Employmentagency_Id Employmentagency_Id,
					t.Weekcard_Id weekcard_Id,
					t.Billable  Billable,
                    format(sum(t.Mon+t.Tue+t.Wed+t.Thu+t.Fri+t.Sat+t.Sun),2) Hours,
                    format(sum((t.Mon+t.Tue+t.Wed+t.Thu+t.Fri+t.Sat+t.Sun)*t.Rate_Cost),2) Cost,
                    sum(t.Mon) Mon,
                    sum(t.Tue) Tue,
                    sum(t.Wed) Wed,
                    sum(t.Thu) Thu,
                    sum(t.Fri) Fri,
                    sum(t.Sat) Sat,
                    sum(t.Sun) Sun,
                    t.Notes as Note,e.Sofinumber,e.Employmentagencynote Employmentagencynote
                FROM
                     tblweekcard w
                    LEFT JOIN tbltimecard t
                        ON w.Id = t.Weekcard_Id
                  LEFT JOIN tblemployee e
                        ON t.Employee_Id = e.Id
                WHERE
                   t.Weekcard_Id = '" . $id . "'
               GROUP BY
                    t.Id
                ORDER BY
                    e.Firstname ASC "));
    }
    public function getProjectIdAttribute()
    {
        // logic to determine project_id if needed
        return $this->attributes['project_id'] ?? null;
    }
}
