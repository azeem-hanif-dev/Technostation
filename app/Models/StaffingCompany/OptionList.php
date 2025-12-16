<?php

namespace App\Models\StaffingCompany;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class OptionList extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    public function userRights(): BelongsToMany
    {
        return $this->belongsToMany(UserRight::class);
    }
}
