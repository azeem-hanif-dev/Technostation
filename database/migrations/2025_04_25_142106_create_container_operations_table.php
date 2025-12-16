<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContainerOperationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('container_operations', function (Blueprint $table) {


            $table->increments('id');
            $table->unsignedInteger('order_container_id');
            $table->unsignedInteger('container_type_id');
            $table->integer('placement')->nullable();
            $table->integer('exchange')->nullable();
            $table->integer('discharge')->nullable();
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
        Schema::dropIfExists('container_operations');
    }
}
