<?php

namespace App\Models\StaffingCompany;

use Illuminate\Database\Eloquent\Model;

class ContainerWasteBreakdown extends Model
{
    protected $table = 'container_waste_breakdown';

    protected $fillable = [
        'order_container_id',
        'container_type_id',
        'container_operation_id',
        'bsa',
        'debris',
        'wood',
        'plastic_foil',
        'paper',
        'diverse',
        'comment',
    ];


    public function orderContainer(){
        return $this->belongsTo(OrderContainer::class, 'order_container_id');
    }

    public function containerType()
    {
        return $this->belongsTo(ContainerType::class, 'container_type_id', 'id');
    }

    public function containerOperation()
    {
        return $this->belongsTo(ContainerOperation::class, 'container_operation_id');
    }

}
