<?php

namespace App\Models\StaffingCompany;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RetailOffer extends Model

{
    

     protected $fillable = [
        'date',
        'project_id',
        'service_type',
        'construction_price',
        'traffic_controllers_price',
        'status',
        'notes',
    ];
     public function project()
    {
        return $this->belongsTo(StaffingProject::class);
    }
}
