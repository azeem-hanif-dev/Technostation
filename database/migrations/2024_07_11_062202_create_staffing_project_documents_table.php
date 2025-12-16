<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStaffingProjectDocumentsTable extends Migration
{
    public function up(): void
    {
        Schema::create('staffing_project_documents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('staffing_project_id');
            $table->string('type')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('file')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staffing_project_documents');
    }
}
