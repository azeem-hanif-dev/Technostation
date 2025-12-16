<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampMaintenancesWeekStateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('camp_maintenances_week_state', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('camp_project_id');
            $table->foreign('camp_project_id')->references('id')->on('camp_maintenances_projects')->onDelete('cascade');
            $table->unsignedBigInteger('personnel_id');
            $table->foreign('personnel_id')->references('id')->on('personnels')->onDelete('cascade');
            $table->unsignedBigInteger('supervisor_id')->nullable();
            $table->foreign('supervisor_id')->references('id')->on('contacts')->onDelete('cascade');
            $table->decimal('hours_mon', 5, 2)->default(0);
            $table->decimal('hours_tue', 5, 2)->default(0);
            $table->decimal('hours_wed', 5, 2)->default(0);
            $table->decimal('hours_thu', 5, 2)->default(0);
            $table->decimal('hours_fri', 5, 2)->default(0);
            $table->decimal('hours_sat', 5, 2)->default(0);
            $table->decimal('hours_sun', 5, 2)->default(0);
            $table->decimal('total_hours', 8, 2)->default(0);
            $table->boolean('hours_approved')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('camp_maintenances_week_state');
    }
}
