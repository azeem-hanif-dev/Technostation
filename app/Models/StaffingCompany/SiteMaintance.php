<?php

namespace App\Models\StaffingCompany;

use Illuminate\Database\Eloquent\Model;

class SiteMaintance extends Model
{

    protected $table = 'site_maintances';

    protected $fillable = [
        'date',
        'title',
        'project_id',
        'scope',
        'unit',
        'price_hour',
        'desc_hour',
        'price_time',
        'desc_time',
        'status',
        'our_reference',
    ];

    protected $casts = [
        'scope' => 'array',
    ];
    public function project()
    {
        return $this->belongsTo(StaffingProject::class, 'project_id');
    }
}
