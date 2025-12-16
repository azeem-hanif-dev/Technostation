<?php

namespace App\Models\StaffingCompany;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class containerquotation extends Model
{
    protected $table = 'container_quotations';
    protected $fillable = ['user_id', 'project', 'customer_id', 'contact_person', 'date', 'total'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ContainerQuotation.php
    public function items()
    {
        return $this->hasMany(containerquotationitem::class, 'container_quotation_id');
    }

    public function  customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}
