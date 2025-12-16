<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPersonnelIdColumnInSCTimeCardsTable extends Migration
{
    public function up(): void
    {
        Schema::table('s_c_time_cards', function (Blueprint $table) {
            $table->unsignedBigInteger('personnel_id')->nullable()->after('sf_week_card_id');
        });
    }

    public function down(): void
    {
        Schema::table('s_c_time_cards', function (Blueprint $table) {
            $table->dropColumn('personnel_id');
        });
    }
}
