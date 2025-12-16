<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeGroupIdColumnToNullableInEmployeeProjectPlannings extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE employee_project_plannings MODIFY group_id BIGINT DEFAULT NULL');
    }

    public function down(): void
    {

    }
}
