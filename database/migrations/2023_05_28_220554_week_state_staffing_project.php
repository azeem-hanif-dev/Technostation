<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class WeekStateStaffingProject extends Migration
{
    public function up(): void
    {
        Schema::create('week_state_staffing_project', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('week_state_id');
            $table->unsignedBigInteger('staffing_project_id');
            $table->foreign('week_state_id')->references('id')->on('week_states')->onDelete('cascade');
            $table->foreign('staffing_project_id')->references('id')->on('staffing_projects')->onDelete('cascade');
            $table->timestamps();
        });
    }
}
