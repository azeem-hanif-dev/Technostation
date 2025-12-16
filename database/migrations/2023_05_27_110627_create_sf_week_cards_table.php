<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSfWeekCardsTable extends Migration
{
    public function up(): void
    {
        Schema::create('sf_week_cards', function (Blueprint $table) {
            $table->increments('id');
            $table->foreign('week_state_id')->references('id')->on('week_states')->onDelete('cascade');
            $table->unsignedInteger('week_state_id');
            $table->foreign('personnel_id')->references('id')->on('personnels')->onDelete('cascade');
            $table->unsignedInteger('personnel_id');
            $table->integer('hours_1')->nullable();
            $table->integer('hours_2')->nullable();
            $table->integer('hours_3')->nullable();
            $table->integer('hours_4')->nullable();
            $table->integer('hours_5')->nullable();
            $table->integer('hours_6')->nullable();
            $table->integer('hours_7')->nullable();
            $table->integer('total_hours')->nullable();
            $table->integer('customer');
            $table->integer('cost');
            $table->tinyInteger('directing');
            $table->string('comments');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sf_week_cards');
    }
}
