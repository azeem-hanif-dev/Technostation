<?php

use App\Models\StaffingCompany\EmployeeFunction;
use Illuminate\Database\Seeder;

class EmployeeFunctionTableSeeder extends Seeder
{
    public function run()
    {
        $employee_functions = EmployeeFunction::all();

        foreach ($employee_functions as $employee_function) {
            $employee_function->code = $this->generateCode($employee_function->name);
            $employee_function->save();
        }
    }

    private function generateCode($name): string
    {
        $words = explode(' ', $name);
        $code = '';
        foreach ($words as $word) {
            $code .= substr($word, 0, 2);
        }

        return strtolower($code);
    }
}
