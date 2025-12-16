<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('date');
            $table->string('subject');
            $table->unsignedBigInteger('project_id');
            $table->string('our_reference');
            $table->json('scope'); 
            $table->decimal('total_price', 13, 2);
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
        Schema::dropIfExists('offers');
    }
}
