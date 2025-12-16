<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampProjectDocumentsTable extends Migration
{
    public function up()
    {
        Schema::create('camp_project_documents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('camp_project_id');
            $table->string('type')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('file')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('camp_project_id')
                ->references('id')
                ->on('camp_maintenances_projects')
                ->onDelete('cascade');
        });
    }
    public function down()
    {
        Schema::dropIfExists('camp_project_documents');
    }
}
