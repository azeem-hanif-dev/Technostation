<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectPlanningsTable extends Migration
{
    public function up(): void
    {
        Schema::create('project_plannings', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_plannings');
    }
}
