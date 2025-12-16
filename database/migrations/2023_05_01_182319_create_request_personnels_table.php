<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestPersonnelsTable extends Migration
{
    public function up(): void
    {
        Schema::create('request_personnels', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreign('project_id')->references('id')->on('staffing_projects');
            $table->unsignedInteger('project_id');
            $table->foreign('function_id')->references('id')->on('employee_functions');
            $table->unsignedInteger('function_id');
            $table->string('application_no');
            $table->string('phone')->nullable();
            $table->string('adopted_by');
            $table->dateTime('application_date_time');
            $table->dateTime('starting_date_time');
            $table->integer('no_of_people');
            $table->integer('days');
            $table->string('report_to');
            $table->string('mobile');
            $table->text('requirements');
            $table->text('notes');
            $table->text('comments');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('request_personnels');
    }
}
