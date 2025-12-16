<?php

namespace App\Models\StaffingCompany;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
  protected $fillable = [
        'user_id', 'user_name', 'action', 'model', 'url', 'description', 'ip_address',
    ];
}
