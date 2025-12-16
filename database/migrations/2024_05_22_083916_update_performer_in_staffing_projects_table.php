<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePerformerInStaffingProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('staffing_projects', function (Blueprint $table) {
            $table->dropColumn('performer');
        });

        Schema::table('staffing_projects', function (Blueprint $table) {
            $table->unsignedInteger('performer');
            $table->foreign('performer')->references('id')->on('contacts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('staffing_projects', function (Blueprint $table) {
            $table->dropForeign(['performer']);
            $table->dropColumn('performer');
        });

        Schema::table('staffing_projects', function (Blueprint $table) {
            $table->string('performer');
        });
    }
}
