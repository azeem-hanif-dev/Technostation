<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWeekStateDocumentsTable extends Migration
{
    public function up(): void
    {
        Schema::create('week_state_documents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('week_state_id');
            $table->string('type')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('file')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('week_state_documents');
    }
}
