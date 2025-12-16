<?php

use App\Models\StaffingCompany\OrderContainer;
use Illuminate\Database\Seeder;

class OrderContainerTableSeeder extends Seeder
{

    public function run()
    {
        $order_container = OrderContainer::create([
            'project_id' => 1,
            'container_supplier_id' => 1,
            'order_date_time' => '2023-05-15 08:22:00',
            'execution_date' => '2023-01-01',
            'approved_by' => 'sample',
            'order_by' => 'sample',
            'part_of_day' => 'sample',
            'notes' => 'sample',
            'comments' => 'sample',
        ]);

        $order_container = OrderContainer::create([
            'project_id' => 3,
            'container_supplier_id' => 3,
            'order_date_time' => '2023-05-15 08:22:00',
            'execution_date' => '2023-01-01',
            'approved_by' => 'sample',
            'order_by' => 'sample',
            'part_of_day' => 'sample',
            'notes' => 'sample',
            'comments' => 'sample',
        ]);


        $order_container = OrderContainer::create([
            'project_id' => 2,
            'container_supplier_id' => 2,
            'order_date_time' => '2023-05-15 08:22:00',
            'execution_date' => '2023-01-01',
            'approved_by' => 'sample',
            'order_by' => 'sample',
            'part_of_day' => 'sample',
            'notes' => 'sample',
            'comments' => 'sample',
        ]);
    }
}
