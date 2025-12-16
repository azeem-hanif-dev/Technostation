<?php

use Illuminate\Database\Migrations\Migration;

class ChangeColumnNullableInPersonnels extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE personnels MODIFY function_id BIGINT DEFAULT NULL');
        DB::statement('ALTER TABLE personnels MODIFY salutation VARCHAR(255) DEFAULT NULL');
        DB::statement('ALTER TABLE personnels MODIFY dob date DEFAULT NULL');
        DB::statement('ALTER TABLE personnels MODIFY social_security_number VARCHAR(255) DEFAULT NULL');
        DB::statement('ALTER TABLE personnels MODIFY mobile VARCHAR(255) DEFAULT NULL');
        DB::statement('ALTER TABLE personnels MODIFY email VARCHAR(255) DEFAULT NULL');
        DB::statement('ALTER TABLE personnels MODIFY password VARCHAR(255) DEFAULT NULL');
        DB::statement('ALTER TABLE personnels MODIFY id_type VARCHAR(255) DEFAULT NULL');
        DB::statement('ALTER TABLE personnels MODIFY id_number VARCHAR(255) DEFAULT NULL');
        DB::statement('ALTER TABLE personnels MODIFY id_expiry date DEFAULT NULL');
        DB::statement('ALTER TABLE personnels MODIFY nationality VARCHAR(255) DEFAULT NULL');
        DB::statement('ALTER TABLE personnels MODIFY address VARCHAR(255) DEFAULT NULL');
        DB::statement('ALTER TABLE personnels MODIFY postcode VARCHAR(255) DEFAULT NULL');
        DB::statement('ALTER TABLE personnels MODIFY city VARCHAR(255) DEFAULT NULL');
        DB::statement('ALTER TABLE personnels MODIFY active boolean DEFAULT NULL');
        DB::statement('ALTER TABLE personnels MODIFY rate_per_hour VARCHAR(255) DEFAULT NULL');
        DB::statement('ALTER TABLE personnels MODIFY cost_per_hour VARCHAR(255) DEFAULT NULL');
    }

    public function down(): void
    {

    }
}
