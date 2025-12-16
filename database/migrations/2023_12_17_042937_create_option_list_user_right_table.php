<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOptionListUserRightTable extends Migration
{
    public function up(): void
    {
        Schema::create('option_list_user_right', function (Blueprint $table) {
            $table->unsignedBigInteger('option_list_id');
            $table->unsignedBigInteger('user_right_id');
            $table->foreign('option_list_id')->references('id')->on('option_lists')->onDelete('cascade');
            $table->foreign('user_right_id')->references('id')->on('user_rights')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('option_list_user_right');
    }
}
