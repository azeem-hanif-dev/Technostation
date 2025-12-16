<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderContainersTable extends Migration
{
    public function up(): void
    {
        Schema::create('order_containers', function (Blueprint $table) {
            $table->increments('id');
            $table->foreign('project_id')->references('id')->on('staffing_projects');
            $table->unsignedInteger('project_id');
            $table->foreign('container_supplier_id')->references('id')->on('container_suppliers')->onDelete('cascade');
            $table->unsignedInteger('container_supplier_id');
            $table->dateTime('order_date_time');
            $table->string('order_by');
            $table->date('execution_date');
            $table->string('approved_by');
            $table->string('part_of_day');
            $table->text('notes')->nullable();
            $table->text('comments')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_containers');
    }
}
