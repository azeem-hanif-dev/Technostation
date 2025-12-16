<?php

namespace App\Models;

use App\Models\StaffingCompany\EmploymentAgencyDocument;
use App\Models\StaffingCompany\Personnel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;


class EmployAgency extends Model
{
  use Notifiable;
  public function users(): HasMany
  {
    return $this->hasMany(User::class);
  }

  public function personnels(): HasMany
  {
    return $this->hasMany(Personnel::class);
  }

  public function company(): BelongsTo
  {
    return $this->belongsTo(User::class, 'company_id');
  }

  public function documents(): HasMany
  {
    return $this->hasMany(EmploymentAgencyDocument::class, 'employment_agency_id');
  }
  public function employagencyoverview()
  {
    return $this->hasOne(EmploymentAgencyOverview::class, 'employ_agency_id', 'id');
  }
}
