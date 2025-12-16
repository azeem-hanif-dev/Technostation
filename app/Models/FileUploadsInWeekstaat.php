<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class FileUploadsInWeekstaat extends Model
{

    protected $fillable =  [
        'WeekNumber',
        'ProjectId',
        'PlanningId',
        'FileName',
        'signature',
        'approved_by_id',
    ];


    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by_id');
    }
}
