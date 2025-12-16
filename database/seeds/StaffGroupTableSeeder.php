<?php

use App\Models\StaffingCompany\StaffGroup;
use Illuminate\Database\Seeder;

class StaffGroupTableSeeder extends Seeder
{
    public function run()
    {
        $groups = array_map(function ($char) {
            return 'Group ' . $char;
        }, range('A', 'Z'));

        foreach ($groups as $group)
        {
            StaffGroup::create([
               'name' => $group,
            ]);
        }
    }
}
