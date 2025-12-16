<?php

use App\Models\StaffingCompany\Contact;
use Illuminate\Database\Seeder;

class ContactTableSeeder extends Seeder
{
    public function run()
    {
        Contact::create([
            'department_id' => 7,
            'salutation' => 'Mr',
            'initials' => 'al',
            'first_name' => 'Ali',
            'last_name' => 'Raza',
            'function_text' => 'Nothing',
            'active' => 1,
            'dates' => '2023-03-03',
            'telephone' => '09000544',
            'private_phone' => '0664649',
            'mobile' => '00366596',
            'mobile1' => '1564556',
            'fax' => '0365959',
            'email' => 'sample@gmail.com',
            'password' => Hash::make('123456'),
        ]);

        Contact::create([
            'department_id' => 2,
            'salutation' => 'Ms',
            'initials' => 'as',
            'first_name' => 'Aiman',
            'last_name' => 'Raza',
            'function_text' => 'Nothing',
            'active' => 1,
            'dates' => '2023-03-03',
            'telephone' => '09000544',
            'private_phone' => '0664649',
            'mobile' => '00366596',
            'mobile1' => '1564556',
            'fax' => '0365959',
            'email' => 'sample@gmail.com',
            'password' => Hash::make('123456'),
        ]);

        Contact::create([
            'department_id' => 3,
            'salutation' => 'Mr',
            'initials' => 'al',
            'first_name' => 'Ahmad',
            'last_name' => 'Naeem',
            'function_text' => 'Nothing',
            'active' => 1,
            'dates' => '2023-03-03',
            'telephone' => '09000544',
            'private_phone' => '0664649',
            'mobile' => '00366596',
            'mobile1' => '1564556',
            'fax' => '0365959',
            'email' => 'sample@gmail.com',
            'password' => Hash::make('123456'),
        ]);

        Contact::create([
            'department_id' => 8,
            'salutation' => 'Mr',
            'initials' => 'al',
            'first_name' => 'Waleed',
            'last_name' => 'Umer',
            'function_text' => 'Nothing',
            'active' => 1,
            'dates' => '2023-03-03',
            'telephone' => '09000544',
            'private_phone' => '0664649',
            'mobile' => '00366596',
            'mobile1' => '1564556',
            'fax' => '0365959',
            'email' => 'sample@gmail.com',
            'password' => Hash::make('123456'),
        ]);

        Contact::create([
            'department_id' => 2,
            'salutation' => 'Mr',
            'initials' => 'al',
            'first_name' => 'Faizan',
            'last_name' => 'Ziad',
            'function_text' => 'Nothing',
            'active' => 1,
            'dates' => '2023-03-03',
            'telephone' => '09000544',
            'private_phone' => '0664649',
            'mobile' => '00366596',
            'mobile1' => '1564556',
            'fax' => '0365959',
            'email' => 'sample@gmail.com',
            'password' => Hash::make('123456'),
        ]);
    }
}
