<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContainerWasteBreakdownTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('container_waste_breakdown', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('order_container_id');
            $table->unsignedInteger('container_type_id');
            $table->integer('bsa')->nullable();
            $table->integer('debris')->nullable();
            $table->integer('wood')->nullable();
            $table->integer('plastic_foil')->nullable();
            $table->integer('paper')->nullable();
            $table->integer('diverse')->nullable();
            $table->string('comment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('container_waste_breakdown');
    }
}
