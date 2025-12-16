<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFileUploadsInWeekstaatsTable extends Migration
{
    public function up(): void
    {
        Schema::create('file_uploads_in_weekstaats', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('WeekNumber');
            $table->integer('ProjectId');
            $table->integer('PlanningId');
            $table->string('FileName');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('file_uploads_in_weekstaats');
    }
}
