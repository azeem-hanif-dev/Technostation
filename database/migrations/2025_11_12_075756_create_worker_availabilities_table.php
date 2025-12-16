<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkerAvailabilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('worker_availabilities', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('employee_id');
            $table->date('date');
            $table->json('response')->nullable();
            $table->string('device_token')->nullable();
            $table->timestamps();
            $table->foreign('employee_id')
                ->references('id')
                ->on('personnels')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('worker_availabilities');
    }
}
