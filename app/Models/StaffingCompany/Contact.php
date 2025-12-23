<?php

namespace App\Models\StaffingCompany;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\StaffingCompany\StaffingProject;

class Contact extends Model
{

    protected $table = 'contacts';
    protected $with = ['staffingprojects', 'user'];
    //protected $with = ['department', 'staffingprojects', 'user'];

    protected $fillable = [
        'first_name',
        'last_name',
    ];
    use SoftDeletes;

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    // NEW MANY-TO-MANY RELATION
    public function departments(): BelongsToMany
    {
        return $this->belongsToMany(Department::class, 'contact_department');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function staffingprojects()
    {
        return $this->hasMany(StaffingProject::class, 'performer', 'id');
    }
}
