<?php

use App\Models\StaffingCompany\Department;
use Illuminate\Database\Seeder;

class DepartmentTableSeeder extends Seeder
{
    public function run()
    {
        Department::create([
            'customer_id' => 1,
            'name' => 'Sales',
            'address' => 'lahore',
            'postcode' => '422',
            'city' => 'Lahore',
            'mailbox' => 'nothing',
            'postal_code' => '042',
            'po_box_city' => 'LHR',
            'phone' => '090078601',
            'fax' => '090078601',
            'email' => 'abcd@gmail.com',
        ]);

        Department::create([
            'customer_id' => 2,
            'name' => 'Marketing',
            'address' => 'multan pk',
            'postcode' => '0548',
            'city' => 'multan',
            'mailbox' => 'nothing',
            'postal_code' => '266',
            'po_box_city' => 'MUL',
            'phone' => '0365959',
            'fax' => '546987',
            'email' => '45ww5@gmail.com',
        ]);

        Department::create([
            'customer_id' => 3,
            'name' => 'Purchasing',
            'address' => 'karachi pk',
            'postcode' => '789',
            'city' => 'karachi',
            'mailbox' => 'nothing',
            'postal_code' => '5555',
            'po_box_city' => 'KCH',
            'phone' => '66665568',
            'fax' => '578578556',
            'email' => '548w2@gmail.com',
        ]);

        Department::create([
            'customer_id' => 4,
            'name' => 'Export',
            'address' => 'Islamabad pk',
            'postcode' => '0548',
            'city' => 'Islamabad',
            'mailbox' => 'nothing',
            'postal_code' => '55',
            'po_box_city' => 'ISL',
            'phone' => '556546',
            'fax' => '5646354',
            'email' => 'sjsjssjjs@gmail.com',
        ]);
    }
}
