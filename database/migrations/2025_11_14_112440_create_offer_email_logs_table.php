<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOfferEmailLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offer_email_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('offer_id');
            $table->string('offer_type');
            $table->string('to_email');
            $table->string('subject')->nullable();
            $table->string('pdf_path')->nullable();
            $table->enum('email_type', ['initial', 'reminder', 'resent'])->default('initial');
            $table->timestamp('sent_at');
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
        Schema::dropIfExists('offer_email_logs');
    }
}
