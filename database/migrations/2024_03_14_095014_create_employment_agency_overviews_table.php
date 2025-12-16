<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmploymentAgencyOverviewsTable extends Migration
{
    public function up(): void
    {
        Schema::create('employment_agency_overviews', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('employ_agency_id')->nullable();
            $table->date('dispatch_date')->nullable();
            $table->date('receive_date')->nullable();
            $table->date('invoice_date')->nullable();
            $table->string('invoice_number')->nullable();
            $table->string('status')->nullable();
            $table->boolean('completed')->nullable();
            $table->text('comments')->nullable();
            $table->text('notes')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employment_agency_overviews');
    }
}
