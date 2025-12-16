<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserIdColumnInContactsTable extends Migration
{
    public function up(): void
    {
        try {
            DB::statement("SET sql_mode = ''");

            Schema::table('contacts', function (Blueprint $table) {
                $table->bigInteger('user_id')->unsigned()->nullable()->after('id');
            });
        } catch (\Exception $e) {
            \Log::error('Error adding user_id column: ' . $e->getMessage());
        } finally {
            DB::statement("SET sql_mode = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION'");
        }
    }

    public function down(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->dropColumn('user_id');
        });
    }
}
