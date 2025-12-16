<?php

use Illuminate\Database\Seeder;
use seeds\DepartmentSeeder;

class DatabaseSeeder extends Seeder
{

    public function run()
    {
        $this->call(RolesTableSeeder::class);

        $this->call(CompaniesTableSeeder::class);

        $this->call(EmployAgencySeeder::class);

        $this->call(UsersTableSeeder::class);

        $this->call(PermissionsTableSeeder::class);

        $this->call(WorkerTypesSeeder::class);

        $this->call(SupplierTableSeeder::class);

        $this->call(MaterialTableSeeder::class);

        $this->call(CustomerTableSeeder::class);

        $this->call(FloorTableSeeder::class);

        $this->call(ProjectTableSeeder::class);

        $this->call(AreaTableSeeder::class);

        $this->call(JobsTableSeeder::class);

        $this->call(ElementsTableSeeder::class);

        $this->call(TaskTableSeeder::class);

        $this->call(FloorTypeTableSeeder::class);

        $this->call(JobStatusTableSeeder::class);

        $this->call(ProjectCostEstimateTableSeeder::class);

        $this->call(CorrectionStandardsTableSeeder::class);

        $this->call(RoomTypesTableSeeder::class);

        $this->call(RoomTypeFloorTypeTableSeeder::class);

        $this->call(LeaveTypesTableSeeder::class);

        $this->call(StatusTableSeeder::class);

        $this->call(LeavesTableSeeder::class);

        $this->call(LeaveUSerTableSeeder::class);

        $this->call(DepartmentTableSeeder::class);

        $this->call(NationalitiesTableSeeder::class);

        $this->call(ContactTableSeeder::class);

        $this->call(EmployeeFunctionTableSeeder::class);

        $this->call(PersonnelTableSeeder::class);

        $this->call(StaffGroupTableSeeder::class);

        $this->call(ProjectPlanningTableSeeder::class);

        $this->call(EmployeeProjectPlanningTableSeeder::class);

        $this->call(RequestPersonnelTableSeeder::class);

        $this->call(ContainerSupplierTableSeeder::class);

        $this->call(OrderContainerTableSeeder::class);

        $this->call(OptionListTableSeeder::class);

        $this->call(StaffTypeTableSeeder::class);
    }
}
