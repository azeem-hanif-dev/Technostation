<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSiteMaintancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('site_maintances', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('date');
            $table->string('title');
            $table->unsignedBigInteger('project_id');
            $table->string('our_reference');
            $table->json('scope');
            $table->string('unit');
            $table->decimal('price_hour', 10, 2)->nullable();
            $table->text('desc_hour')->nullable();
            $table->decimal('price_time', 10, 2)->nullable();
            $table->text('desc_time')->nullable();
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
        Schema::dropIfExists('site_maintances');
    }
}
