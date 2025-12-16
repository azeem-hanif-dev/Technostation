<?php

use App\Models\StaffingCompany\ProjectPlanning;
use App\Models\StaffingCompany\StaffingProject;
use Illuminate\Database\Seeder;

class ProjectPlanningTableSeeder extends Seeder
{
    public function run()
    {
        ProjectPlanning::create([
            'date' => '2023-04-21'
        ]);

        ProjectPlanning::create([
            'date' => '2023-04-21'
        ]);

        ProjectPlanning::create([
            'date' => '2023-04-21'
        ]);

        ProjectPlanning::create([
            'date' => '2023-04-21'
        ]);

        ProjectPlanning::create([
            'date' => '2023-04-21'
        ]);


        for ($i = 1; $i < 6; $i++)
        {
            $projectPlanning = ProjectPlanning::find($i);

            $projectPlanning->staffingProjects()->attach($i);
        }
    }
}
