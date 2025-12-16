<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonnelsTable extends Migration
{
    public function up(): void
    {
        Schema::create('personnels', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('employ_agency_id');
            $table->foreign('employ_agency_id')->references('id')->on('employ_agencies');
            $table->unsignedInteger('function_id');
            $table->foreign('function_id')->references('id')->on('employee_functions');
            $table->string('salutation');
            $table->string('initials')->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->date('dob');
            $table->string('social_security_number');
            $table->date('date_service')->default('2000-01-01');
            $table->integer('telephone')->nullable();
            $table->integer('mobile');
            $table->integer('mobile2')->nullable();
            $table->integer('mobile3')->nullable();
            $table->string('email');
            $table->string('password');
            $table->boolean('vca_certificate')->nullable();
            $table->boolean('own_car')->nullable();
            $table->string('id_type');
            $table->string('id_number');
            $table->string('id_expiry');
            $table->string('nationality');
            $table->string('address');
            $table->string('postcode');
            $table->string('city');
            $table->boolean('active');
            $table->string('employment_agency_note')->nullable();
            $table->string('rate_per_hour');
            $table->string('cost_per_hour');
            $table->string('personnel_dates')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('personnels');
    }
}
