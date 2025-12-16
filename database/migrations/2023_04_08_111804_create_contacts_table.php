<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactsTable extends Migration
{
    public function up(): void
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('department_id');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
            $table->string('salutation');
            $table->string('initials');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('function_text');
            $table->boolean('active')->default(false);
            $table->string('dates');
            $table->string('telephone');
            $table->string('private_phone');
            $table->string('mobile');
            $table->string('mobile1');
            $table->string('fax');
            $table->string('email');
            $table->string('password');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Contacts');
    }
}
