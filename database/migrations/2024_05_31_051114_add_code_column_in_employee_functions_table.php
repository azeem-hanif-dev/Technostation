<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCodeColumnInEmployeeFunctionsTable extends Migration
{
    public function up(): void
    {
        Schema::table('employee_functions', function (Blueprint $table) {
            $table->string('code')->nullable()->after('name');
        });
    }

    public function down(): void
    {
        Schema::table('employee_functions', function (Blueprint $table) {
            $table->dropColumn('code');
        });
    }
}
