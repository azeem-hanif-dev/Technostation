<?php

namespace App\Models\StaffingCompany;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MultiServiceOffer extends Model
{


    protected $fillable = [
        'date',
        'title',
        'customer_id',
        'services',
        'total_price',
        'status',
        'notes',
    ];

    protected $casts = [
        'services' => 'array',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function project()
    {
        return $this->belongsTo(StaffingProject::class, 'project_id');
    }
}
