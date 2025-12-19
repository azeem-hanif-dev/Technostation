<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivityLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('activity_logs', function (Blueprint $table) {
       $table->bigIncrements('id');
       $table->unsignedBigInteger('user_id')->nullable();
       $table->string('user_name')->nullable();
       $table->string('action');
       $table->string('model')->nullable();
       $table->text('url')->nullable();
       $table->text('description')->nullable();
       $table->ipAddress('ip_address')->nullable();
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
        Schema::dropIfExists('activity_logs');
    }
}