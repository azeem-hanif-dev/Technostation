<?php

use App\Models\StaffingCompany\StaffType;
use Illuminate\Database\Seeder;

class StaffTypeTableSeeder extends Seeder
{
    public function run(): void
    {
        StaffType::updateOrCreate([
            'name' => 'App User'
        ]);

        StaffType::updateOrCreate([
            'name' => 'Web User'
        ]);
    }
}
