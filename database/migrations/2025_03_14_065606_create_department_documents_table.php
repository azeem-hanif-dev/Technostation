<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDepartmentDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('department_documents', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('department_id'); // foreignId() ko replace kiya
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
            $table->string('type');
            $table->date('expir_date')->nullable();
            $table->string('file');
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
        Schema::dropIfExists('department_documents');
    }
}
