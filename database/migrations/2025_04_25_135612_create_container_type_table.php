<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContainerTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('container_type', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
//            $table->foreign('order_container_id')->references('id')->on('order_containers');
//            $table->unsignedInteger('order_container_id');
//            $table->integer('rolcontainer')->nullable();
//
//            $table->integer('10m3')->nullable();
//            $table->integer('20m3')->nullable();
//            $table->integer('30m3')->nullable();
//            $table->integer('40m3')->nullable();
//            $table->integer('placement')->nullable();
//            $table->integer('exchange')->nullable();
//            $table->integer('discharge')->nullable();
//            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('container_type');
    }
}
