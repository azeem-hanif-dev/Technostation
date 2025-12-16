<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AllColumnsNullableInRequestPersonnelsTable extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE request_personnels MODIFY function_id BIGINT DEFAULT NULL');
        DB::statement('ALTER TABLE request_personnels MODIFY project_id BIGINT DEFAULT NULL');
        DB::statement('ALTER TABLE request_personnels MODIFY application_no VARCHAR(255) DEFAULT NULL');
        DB::statement('ALTER TABLE request_personnels MODIFY adopted_by VARCHAR(255) DEFAULT NULL');
        DB::statement('ALTER TABLE request_personnels MODIFY no_of_people int DEFAULT NULL');
        DB::statement('ALTER TABLE request_personnels MODIFY days int DEFAULT NULL');
        DB::statement('ALTER TABLE request_personnels MODIFY report_to VARCHAR(255) DEFAULT NULL');
        DB::statement('ALTER TABLE request_personnels MODIFY mobile VARCHAR(255) DEFAULT NULL');
        DB::statement('ALTER TABLE request_personnels MODIFY requirements VARCHAR(512) DEFAULT NULL');
        DB::statement('ALTER TABLE request_personnels MODIFY notes VARCHAR(512) DEFAULT NULL');
        DB::statement('ALTER TABLE request_personnels MODIFY comments VARCHAR(512) DEFAULT NULL');
    }

    public function down(): void
    {

    }
}
