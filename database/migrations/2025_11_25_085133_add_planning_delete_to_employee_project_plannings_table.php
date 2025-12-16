<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPlanningDeleteToEmployeeProjectPlanningsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employee_project_plannings', function (Blueprint $table) {
            $table->tinyInteger('planning_delete')->default(0)->after('date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employee_project_plannings', function (Blueprint $table) {
            $table->dropColumn('planning_delete');
        });
    }
}
