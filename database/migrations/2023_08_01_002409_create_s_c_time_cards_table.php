<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSCTimeCardsTable extends Migration
{
    public function up(): void
    {
        Schema::create('s_c_time_cards', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('sf_week_card_id');
            $table->foreign('sf_week_card_id')->references('id')->on('sf_week_cards');
            $table->string('day_name')->nullable();
            $table->time('check_in_time')->nullable();
            $table->time('check_out_time')->nullable();
            $table->string('check_in_location')->nullable();
            $table->string('check_out_location')->nullable();
            $table->time('total_time')->nullable();
            $table->date('date_in')->nullable();
            $table->date('date_out')->nullable();
            $table->tinyInteger('approved')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('s_c_time_cards');
    }
}
