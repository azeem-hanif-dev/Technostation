<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
  public function suppliers() {
    return $this->belongsToMany(Supplier::class, 'suppliers_materials');
  }

  public function tasks() {
    return $this->belongsToMany(Task::class, 'tasks_materials');
  }

  public function projects()
  {
      return $this->belongsToMany('App\Models\Project');
  }
}
