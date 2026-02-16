<?php

namespace Modules\HumanResources\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\HumanResources\Models\Employee;
use Modules\HumanResources\Models\EmployeeSalary;

class DummyEmployeeSeeder extends Seeder
{
    public function run()
    {
        $employee = Employee::updateOrCreate(
            ['email' => 'muhasebe_test@erpovy.com'],
            [
                'company_id' => 1,
                'first_name' => 'Ahmet',
                'last_name' => 'Yılmaz',
                'phone' => '05554443322',
                'department_id' => 1,
                'position' => 'Muhasebe Uzmanı',
                'hire_date' => '2024-01-01',
                'status' => 'active',
                'salary' => 45000.00,
            ]
        );

        EmployeeSalary::updateOrCreate(
            ['employee_id' => $employee->id, 'is_active' => true],
            [
                'amount' => 45000.00,
                'start_date' => '2026-01-01',
                'type' => 'gross',
                'is_active' => true,
            ]
        );
    }
}
