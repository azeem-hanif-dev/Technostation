<?php

namespace App\Models\StaffingCompany;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserRight extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    public function rightsList(): BelongsToMany
    {
        return $this->belongsToMany(OptionList::class);
    }
}
