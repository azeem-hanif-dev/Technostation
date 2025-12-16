<?php

namespace App\Models\StaffingCompany;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderContainerPriceList extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    public function orderContainer()
    {
        return $this->belongsTo(OrderContainer::class,'order_container_id');
    }
}
