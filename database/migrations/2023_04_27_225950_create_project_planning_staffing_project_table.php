<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectPlanningStaffingProjectTable extends Migration
{
    public function up()
    {
        Schema::create('project_planning_staffing_project', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('project_planning_id');
            $table->unsignedBigInteger('staffing_project_id');
            $table->foreign('project_planning_id')->references('id')->on('project_plannings')->onDelete('cascade');
            $table->foreign('staffing_project_id')->references('id')->on('staffing_projects')->onDelete('cascade');
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('project_planning_staffing_project');
    }
}
