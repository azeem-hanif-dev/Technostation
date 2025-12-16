<?php

namespace App\Models\StaffingCompany;

use Illuminate\Database\Eloquent\Model;

class ContainerType extends Model
{
    protected $table = 'container_type';

    protected $fillable = ['name'];

    public function containerOperations()
    {
        return $this->hasMany(ContainerOperation::class, 'container_type_id');
    }
    public function wasteBreakdowns()
    {
        return $this->hasMany(ContainerWasteBreakdown::class, 'container_type_id', 'id');
    }
}
