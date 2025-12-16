<?php

use App\Models\StaffingCompany\RequestPersonnel;
use Illuminate\Database\Seeder;

class RequestPersonnelTableSeeder extends Seeder
{

    public function run()
    {
        RequestPersonnel::create([
            'project_id' => 1,
            'function_id' => 2,
            'application_no' => 'AB-23',
            'phone' => '004894949',
            'adopted_by' => 'test',
            'application_date_time' => '2023-04-13 12:26:43',
            'starting_date_time' => '2023-04-13 12:26:43',
            'no_of_people' => 10,
            'days' => 5,
            'report_to' => 'sample',
            'mobile' => '004894949',
            'requirements' => 'nothing',
            'notes' => 'nothing',
            'comments' => 'nothing',
        ]);

        RequestPersonnel::create([
            'project_id' => 3,
            'function_id' => 4,
            'application_no' => 'AB-5',
            'phone' => '44444444',
            'adopted_by' => 'test',
            'application_date_time' => '2023-04-13 12:26:43',
            'starting_date_time' => '2023-04-13 12:26:43',
            'no_of_people' => 5,
            'days' => 5,
            'report_to' => 'sample',
            'mobile' => '004894949',
            'requirements' => 'nothing',
            'notes' => 'nothing',
            'comments' => 'nothing',
        ]);

        RequestPersonnel::create([
            'project_id' => 2,
            'function_id' => 4,
            'application_no' => 'CB-23',
            'phone' => '555555555',
            'adopted_by' => 'test',
            'application_date_time' => '2023-04-13 12:26:43',
            'starting_date_time' => '2023-04-13 12:26:43',
            'no_of_people' => 8,
            'days' => 5,
            'report_to' => 'sample',
            'mobile' => '004894949',
            'requirements' => 'nothing',
            'notes' => 'nothing',
            'comments' => 'nothing',
        ]);

        RequestPersonnel::create([
            'project_id' => 5,
            'function_id' => 1,
            'application_no' => 'DF-23',
            'phone' => '004894949',
            'adopted_by' => 'test',
            'application_date_time' => '2023-04-13 12:26:43',
            'starting_date_time' => '2023-04-13 12:26:43',
            'no_of_people' => 2,
            'days' => 3,
            'report_to' => 'sample',
            'mobile' => '004894949',
            'requirements' => 'nothing',
            'notes' => 'nothing',
            'comments' => 'nothing',
        ]);

        RequestPersonnel::create([
            'project_id' => 3,
            'function_id' => 5,
            'application_no' => 'AB-23',
            'phone' => '004894949',
            'adopted_by' => 'test',
            'application_date_time' => '2023-04-13 12:26:43',
            'starting_date_time' => '2023-04-13 12:26:43',
            'no_of_people' => 1,
            'days' => 1,
            'report_to' => 'sample',
            'mobile' => '004894949',
            'requirements' => 'nothing',
            'notes' => 'nothing',
            'comments' => 'nothing',
        ]);
    }
}
