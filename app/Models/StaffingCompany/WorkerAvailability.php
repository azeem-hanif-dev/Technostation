<?php

namespace App\Models\StaffingCompany;

use Illuminate\Database\Eloquent\Model;
use App\Models\StaffingCompany\Personnel;

class WorkerAvailability extends Model
{
    protected $fillable = ['employee_id', 'date', 'response'];
    
    protected $casts = [
        'response' => 'array',
    ];
    public function personnel()
    {
        return $this->belongsTo(Personnel::class, 'employee_id', 'id');
    }
}
