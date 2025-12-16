<?php

namespace App\Models\StaffingCompany;

use Illuminate\Database\Eloquent\Model;

class ContainerOperation extends Model
{
    protected $table = 'container_operations';

    protected $fillable = [
        'order_container_id',
        'container_type_id',
        'placement',
        'exchange',
        'discharge',
    ];

    public function containerType()
    {
        return $this->belongsTo(ContainerType::class, 'container_type_id');
    }

    public function wasteBreakdown()
    {
        return $this->hasMany(ContainerWasteBreakdown::class, 'container_operation_id');
    }
    public function orderContainer(){
        return $this->belongsTo(OrderContainer::class, 'order_container_id');
    }
}
