<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSCLeavesTable extends Migration
{
    public function up(): void
    {
        Schema::create('s_c_leaves', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('details');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('leave_type');
            $table->string('leave_day_count');
            $table->bigInteger('requested_by');
            $table->tinyInteger('status')->default(0)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('s_c_leaves');
    }
}
