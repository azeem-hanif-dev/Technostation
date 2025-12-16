<?php

namespace App\Models\StaffingCompany;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffingProjectDocument extends Model
{
    protected $table = 'staffing_project_documents';

    protected $fillable = [
        'type',
        'expiry_date',
        'file',
    ];

    public function staffingProject(): BelongsTo
    {
        return $this->belongsTo(StaffingProject::class);
    }
}
