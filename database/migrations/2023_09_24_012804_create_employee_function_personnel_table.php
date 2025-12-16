<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeFunctionPersonnelTable extends Migration
{
    public function up(): void
    {
        Schema::create('employee_function_personnel', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('personnel_id');
            $table->unsignedBigInteger('employee_function_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('personnel_employee_function');
    }
}
