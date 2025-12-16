<?php

namespace App\Models\StaffingCompany;

use App\Models\Customer;
use App\StaffingCompany\DepartmentDocument;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    protected $table = 'departments';

    use SoftDeletes;

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function staffingProjects(): HasMany
    {
        return $this->hasMany(StaffingProject::class);
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class);
    }

    public function documents()
    {
        return $this->hasMany(DepartmentDocument::class);
    }
}
