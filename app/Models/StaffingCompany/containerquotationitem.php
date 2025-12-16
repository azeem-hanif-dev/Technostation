<?php

namespace App\Models\StaffingCompany;

use Illuminate\Database\Eloquent\Model;

class containerquotationitem extends Model
{

  protected $table = 'container_quotation_items';
  protected $fillable = ['container_quotation_id', 'date', 'content', 'waste_type', 'weight', 'price_per_ton', 'extra_charge', 'amount', 'remarks'];


  public function quotation()
  {
    return $this->belongsTo(containerquotation::class, 'container_quotation_id');
  }
}
