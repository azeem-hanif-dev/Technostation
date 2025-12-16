<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgencyOverviewDocumentsTable extends Migration
{
    public function up(): void
    {
        Schema::create('agency_overview_documents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('employment_agency_overview_id');
            $table->string('type')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('file')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agency_overview_documents');
    }
}
