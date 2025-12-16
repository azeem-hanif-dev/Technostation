<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AllowNullableLatLongInStaffingProjects extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('staffing_projects', function (Blueprint $table) {
            $table->string('lat')->nullable()->change();
            $table->string('long')->nullable()->change();
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
            $table->string('lat')->nullable(false)->change();
            $table->string('long')->nullable(false)->change();
        });
    }
}
