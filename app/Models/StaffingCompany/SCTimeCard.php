<?php

namespace App\Models\StaffingCompany;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SCTimeCard extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    public function weekCard() {
        return $this->belongsTo(SfWeekCard::class, 'sf_week_card_id');
    }

    public function personnel()
{
    return $this->belongsTo(Personnel::class);
}
}
