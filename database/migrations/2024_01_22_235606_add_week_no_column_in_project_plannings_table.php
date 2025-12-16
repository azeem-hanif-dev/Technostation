<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWeekNoColumnInProjectPlanningsTable extends Migration
{
    public function up(): void
    {
        Schema::table('project_plannings', function (Blueprint $table) {
            $table->string('week_no')->nullable()->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('project_plannings', function (Blueprint $table) {
            $table->dropColumn('week_no');
        });
    }
}
