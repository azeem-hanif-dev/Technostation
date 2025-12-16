<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWeekNoColumnInSfWeekCardsTable extends Migration
{
    public function up(): void
    {
        Schema::table('sf_week_cards', function (Blueprint $table) {
            $table->string('week_no')->nullable()->after('personnel_id');
        });
    }

    public function down(): void
    {
        Schema::table('sf_week_cards', function (Blueprint $table) {
            $table->dropColumn('week_no');
        });
    }
}
