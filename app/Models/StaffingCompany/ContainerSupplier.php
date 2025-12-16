<?php

namespace App\Models\StaffingCompany;

use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContainerSupplier extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    protected $with = ['priceLists'];

    public function priceLists()
    {
        return $this->hasMany(ContainerSupplierPriceList::class);
    }

    public function orderContainers()
    {
        return $this->hasMany(OrderContainer::class);
    }
    public function containerSupplierUserInfo()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
