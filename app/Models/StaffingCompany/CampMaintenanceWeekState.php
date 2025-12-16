<?php

namespace App\Models\StaffingCompany;

use App\Models\StaffingCompany\Contact;
use Illuminate\Database\Eloquent\Model;
use App\Models\StaffingCompany\Personnel;
use App\Models\StaffingCompany\CampMaintenanceProject;

class CampMaintenanceWeekState extends Model
{
    protected $table = 'camp_maintenances_week_state';
    protected $guarded = [];

    public function campProject()
    {
        return $this->belongsTo(CampMaintenanceProject::class, 'camp_project_id');
    }

    public function personnel()
    {
        return $this->belongsTo(Personnel::class, 'personnel_id');
    }

    public function supervisor()
    {
        return $this->belongsTo(Contact::class, 'supervisor_id');
    }
}
