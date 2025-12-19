<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStaffingProjectsTable extends Migration
{
    public function up(): void
    {
        Schema::create('staffing_projects', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('customer_id');
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->unsignedInteger('department_id');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');;
            $table->string('name');
            $table->string('performer');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('project_manager');
            $table->longText('description');
            $table->string('fixed_price');
            $table->string('edu_project_no');
            $table->string('client_project_no');
            $table->string('address');
            $table->string('post_code');
            $table->string('city');
            $table->string('lat');
            $table->string('long');
            $table->string('weekly_statement');
            $table->string('price_agreement');
            $table->string('no_of_times_per_week');
            $table->string('unit');
            $table->string('no_of_chain');
            $table->string('price');
            $table->string('purchase_price')->nullable();
            $table->string('dates')->nullable();
            $table->string('approval')->nullable();
            $table->string('notes')->nullable();
            $table->boolean('active')->default(false);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staffing_projects');
    }
}
