<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsInEmploymentAgencyOverviews extends Migration
{
    public function up(): void
    {
        Schema::table('employment_agency_overviews', function (Blueprint $table) {
            $table->string('worked_hours')->nullable()->after('employ_agency_id');
            $table->string('cost')->nullable()->after('worked_hours');
            $table->string('week_no')->nullable()->after('cost');
        });
    }

    public function down(): void
    {
        Schema::table('employment_agency_overviews', function (Blueprint $table) {
            $table->dropColumn('worked_hours','cost');
        });
    }
}
