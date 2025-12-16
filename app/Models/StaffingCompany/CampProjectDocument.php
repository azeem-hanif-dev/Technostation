<?php

namespace App\Models\StaffingCompany;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CampProjectDocument extends Model
{
    use SoftDeletes;

    protected $table = 'camp_project_documents';

    protected $fillable = [
        'camp_project_id',
        'type',
        'expiry_date',
        'file',
    ];
}
