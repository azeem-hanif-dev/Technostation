<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRetailOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('retail_offers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('date');
            $table->unsignedBigInteger('project_id');
            $table->string('service_type');
            $table->decimal('construction_price', 8, 2);
            $table->decimal('traffic_controllers_price', 8, 2);
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'accepted'])->default('pending');
            $table->timestamps();
            $table->foreign('project_id')->references('id')->on('staffing_projects')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('retail_offers');
    }
}
