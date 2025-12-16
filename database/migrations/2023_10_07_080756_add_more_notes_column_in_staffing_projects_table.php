<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMoreNotesColumnInStaffingProjectsTable extends Migration
{
    public function up(): void
    {
        Schema::table('staffing_projects', function (Blueprint $table) {
            $table->text('more_notes')->nullable()->after('notes');
        });
    }

    public function down(): void
    {
        Schema::table('staffing_projects', function (Blueprint $table) {
            $table->dropColumn('more_notes');
        });
    }
}
