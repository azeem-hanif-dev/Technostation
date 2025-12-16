<?php

use App\Models\StaffingCompany\EmployeeProjectPlanning;
use Illuminate\Database\Seeder;

class EmployeeProjectPlanningTableSeeder extends Seeder
{
    public function run()
    {
        EmployeeProjectPlanning::create([
            'project_planning_id' => 1,
            'employee_id' => 1,
            'project_id' => 1,
            'week_no' => '202301',
            'geschikt' => 1,
            'status' => 1,
            'group_id' => 1,
            'notes' => 'Nothing',
        ]);

        EmployeeProjectPlanning::create([
            'project_planning_id' => 2,
            'employee_id' => 2,
            'project_id' => 2,
            'week_no' => '202301',
            'geschikt' => 1,
            'status' => 1,
            'group_id' => 1,
            'notes' => 'Nothing',
        ]);

        EmployeeProjectPlanning::create([
            'project_planning_id' => 2,
            'employee_id' => 2,
            'project_id' => 2,
            'week_no' => '202301',
            'geschikt' => 1,
            'status' => 1,
            'group_id' => 1,
            'notes' => 'Nothing',
        ]);


        EmployeeProjectPlanning::create([
            'project_planning_id' => 1,
            'employee_id' => 3,
            'project_id' => 3,
            'week_no' => '202301',
            'geschikt' => 1,
            'status' => 1,
            'group_id' => 1,
            'notes' => 'Nothing',
        ]);

        EmployeeProjectPlanning::create([
            'project_planning_id' => 1,
            'employee_id' => 4,
            'project_id' => 4,
            'week_no' => '202301',
            'geschikt' => 1,
            'status' => 1,
            'group_id' => 1,
            'notes' => 'Nothing',
        ]);
    }
}
