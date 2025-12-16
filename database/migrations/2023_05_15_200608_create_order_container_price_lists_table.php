<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderContainerPriceListsTable extends Migration
{
    public function up(): void
    {
        Schema::create('order_container_price_lists', function (Blueprint $table) {
            $table->increments('id');
            $table->foreign('order_container_id')->references('id')->on('order_containers')->onDelete('cascade');
            $table->unsignedInteger('order_container_id');
            $table->string('container_type')->nullable();
            $table->string('place')->nullable();
            $table->string('vary')->nullable();
            $table->string('disposal')->nullable();
            $table->string('bsa')->nullable();
            $table->string('debris')->nullable();
            $table->string('hout')->nullable();
            $table->string('plastic_folie')->nullable();
            $table->string('papier')->nullable();
            $table->string('diverse')->nullable();
            $table->string('comments')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_container_price_lists');
    }
}
