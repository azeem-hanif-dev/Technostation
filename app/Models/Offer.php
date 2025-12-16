<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\StaffingCompany\StaffingProject;

class Offer extends Model
{
    protected $fillable = [
        'date',
        'title',
        'subject',
        'project_id',
        'our_reference',
        'scope',
        'status',
        'notes',
        'total_price',

    ];

    protected $casts = [
        'date' => 'date',
        'scope' => 'array',
    ];

    public function project()
    {
        return $this->belongsTo(StaffingProject::class);
    }

    public function emailLogs()
    {
        return $this->morphMany(OfferEmailLog::class, 'offer');
    }

    public function isAccepted()
    {
        return $this->status === 'accepted';
    }
}
