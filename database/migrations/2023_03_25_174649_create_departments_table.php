<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepartmentsTable extends Migration
{
    public function up(): void
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->increments('id');
            $table->Integer('customer_id');
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->string('name');
            $table->string('address');
            $table->string('postcode')->nullable();
            $table->string('city');
            $table->string('mailbox')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('po_box_city')->nullable();
            $table->string('phone');
            $table->string('fax')->nullable();
            $table->string('email');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
}
