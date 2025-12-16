<?php

use App\Models\StaffingCompany\OptionList;
use Illuminate\Database\Seeder;

class OptionListTableSeeder extends Seeder
{
    public function run(): void
    {
        $options = [
            [
                'name' => 'Staff',
            ],
            [
                'name' => 'Staff Functions',
            ],
            [
                'name' => 'Customers',
            ],
            [
                'name' => 'Departments',
            ],
            [
                'name' => 'Projects',
            ],
            [
                'name' => 'Project Planing',
            ],
            [
                'name' => 'Contacts',
            ],
            [
                'name' => 'Request Staff',
            ],
            [
                'name' => 'Container Suppliers',
            ],
            [
                'name' => 'Order Waste Containers',
            ],
            [
                'name' => 'Weekly Statements',
            ],
            [
                'name' => 'Comments',
            ],
            [
                'name' => 'Employment Agency',
            ],
            [
                'name' => 'Rights Module',
            ],
        ];

        foreach ($options as $option) {
            OptionList::updateOrCreate($option);
        }
    }
}
