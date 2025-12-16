<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivityRequestPersonnelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity_request_personnel', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('request_personnel_id');
            $table->unsignedInteger('function_id');

            $table->foreign('request_personnel_id')
                ->references('id')->on('request_personnels')
                ->onDelete('cascade');

            $table->foreign('function_id')
                ->references('id')->on('functions')
                ->onDelete('cascade');

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
        Schema::dropIfExists('activity_request_personnel');
    }
}
