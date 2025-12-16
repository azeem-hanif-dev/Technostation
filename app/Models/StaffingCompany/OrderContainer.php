<?php

namespace App\Models\StaffingCompany;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderContainer extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    public function priceLists() {
        return $this->hasMany(OrderContainerPriceList::class);
    }

    public function project() {
        return $this->belongsTo(StaffingProject::class, 'project_id');
    }

//  change due to login system and
//    public function supplier() {
//        return $this->belongsTo(ContainerSupplier::class, 'container_supplier_id');
//    }
    public function supplier() {
       return $this->belongsTo(ContainerSupplier::class, 'container_supplier_id');
   }
    public function containerOperation() {
        return $this->hasMany(ContainerOperation::class, 'order_container_id');
    }

}
