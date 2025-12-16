<?php

namespace App\Models;

use App\Models\StaffingCompany\containerquotation;
use App\Models\StaffingCompany\CustomerDocument;
use App\Models\StaffingCompany\Department;
use App\Models\StaffingCompany\StaffingProject;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
  protected $table = 'customers';
  protected $fillable = ['name', 'notes', 'company_id', 'user_id', 'slug'];

  public function company(): BelongsTo
  {
    return $this->belongsTo(User::class, 'company_id');
  }

  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class, 'user_id');
  }

  public function documents(): HasMany
  {
    return $this->hasMany(CustomerDocument::class, 'customer_id');
  }

  public function projects(): HasMany
  {
    return $this->hasMany(Project::class);
  }

  public function departments(): HasMany
  {
    return $this->hasMany(Department::class);
  }
  public function staffingproject()
  {
    return $this->hasMany(StaffingProject::class);
  }

  public function quotations()
  {
    return $this->hasMany(containerquotation::class, 'customer_id');
  }
}
