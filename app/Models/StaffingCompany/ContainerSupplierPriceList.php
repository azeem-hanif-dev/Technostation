<?php

namespace App\Models\StaffingCompany;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContainerSupplierPriceList extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    public function containerSupplier()
    {
        return $this->belongsTo(ContainerSupplier::class,'container_supplier_id');
    }
}
