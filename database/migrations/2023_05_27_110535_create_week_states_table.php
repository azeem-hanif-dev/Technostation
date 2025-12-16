<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWeekStatesTable extends Migration
{
    public function up(): void
    {
        Schema::create('week_states', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('week_no');
            $table->string('invoice_no');
            $table->date('delay_date');
            $table->date('receive_date');
            $table->date('invoice_date');
            $table->string('status');
            $table->tinyInteger('approved')->default(0);
            $table->tinyInteger('via_worksheet')->default(0);
            $table->text('comments')->nullable();
            $table->text('internal_notes')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('week_states');
    }
}
