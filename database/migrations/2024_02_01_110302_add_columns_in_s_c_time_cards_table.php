<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsInSCTimeCardsTable extends Migration
{
    public function up(): void
    {
        Schema::table('s_c_time_cards', function (Blueprint $table) {
            $table->time('time_pause')->nullable()->after('date_out');
            $table->time('time_resume')->nullable()->after('time_pause');
        });
    }

    public function down(): void
    {
        Schema::table('s_c_time_cards', function (Blueprint $table) {
            $table->dropColumn('time_pause');
            $table->dropColumn('time_resume');
        });
    }
}
