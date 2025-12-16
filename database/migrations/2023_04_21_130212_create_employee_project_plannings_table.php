<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeProjectPlanningsTable extends Migration
{
    public function up(): void
    {
        Schema::create('employee_project_plannings', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('project_planning_id');
            $table->foreign('project_planning_id')->references('id')->on('project_plannings')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('personnels');
            $table->unsignedInteger('employee_id');
            $table->foreign('group_id')->references('id')->on('staff_groups');
            $table->unsignedInteger('group_id');
            $table->foreign('project_id')->references('id')->on('staffing_projects');
            $table->unsignedInteger('project_id');
            $table->string('week_no');
            $table->string('geschikt');
            $table->tinyInteger('status');
            $table->text('notes');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_project_plannings');
    }
}
