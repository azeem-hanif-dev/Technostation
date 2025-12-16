<?php

use Illuminate\Database\Migrations\Migration;

class ChangeColumnNullableInDepartments extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE departments MODIFY address VARCHAR(255) DEFAULT NULL');
        DB::statement('ALTER TABLE departments MODIFY city VARCHAR(255) DEFAULT NULL');
        DB::statement('ALTER TABLE departments MODIFY phone VARCHAR(255) DEFAULT NULL');
        DB::statement('ALTER TABLE departments MODIFY email VARCHAR(255) DEFAULT NULL');
    }

    public function down(): void
    {

    }
}
