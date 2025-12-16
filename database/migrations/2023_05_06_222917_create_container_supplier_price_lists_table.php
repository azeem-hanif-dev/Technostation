<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContainerSupplierPriceListsTable extends Migration
{
    public function up(): void
    {
        Schema::create('container_supplier_price_lists', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreign('container_supplier_id')->references('id')->on('container_suppliers')->onDelete('cascade');
            $table->unsignedInteger('container_supplier_id');
            $table->string('article_no')->nullable();
            $table->string('description')->nullable();
            $table->string('price')->nullable();
            $table->string('unit')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('container_supplier_price_lists');
    }
}
