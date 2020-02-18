<?php

namespace Tests\Unit;

use App\Shop\Employees\Employee;
use App\Shop\Employees\Exceptions\CreateEmployeeInvalidArgumentException;
use App\Shop\Employees\Exceptions\EmployeeNotFoundException;
use App\Shop\Employees\Exceptions\UpdateEmployeeInvalidArgumentException;
use App\Shop\Employees\Repositories\EmployeeRepository;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class EmployeeUnitTest extends TestCase
{
    public function testCanSoftDeleteEmployee(): void
    {
        /** @var Employee */
        $employee = factory(Employee::class)->create();
        $employeeRepo = new EmployeeRepository($employee);
        $delete = $employeeRepo->deleteEmployee();

        $this->assertTrue($delete);
        $this->assertDatabaseHas('employees', $employee->toArray());
    }

    public function testFailWhenEmployeeNotFound(): void
    {
        $this->expectException(EmployeeNotFoundException::class);

        $employeeRepo = new EmployeeRepository(new Employee());
        $employeeRepo->findEmployeeById(999);
    }

    public function testCanFindEmployee(): void
    {
        $data = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => 'secret',
        ];

        $employeeRepo = new EmployeeRepository(new Employee());
        $created = $employeeRepo->createEmployee($data);
        $found = $employeeRepo->findEmployeeById($created->id);

        $this->assertInstanceOf(Employee::class, $found);
        $this->assertEquals($data['name'], $found->name);
        $this->assertEquals($data['email'], $found->email);
    }

    public function testFailWhenUpdatingEmployeeNameWithNull(): void
    {
        $this->expectException(UpdateEmployeeInvalidArgumentException::class);

        /** @var Employee */
        $employee = factory(Employee::class)->create();
        $employeeRepo = new EmployeeRepository($employee);
        $employeeRepo->updateEmployee(['name' => null]);
    }

    public function testCanUpdateEmployeePassword(): void
    {
        /** @var Employee */
        $employee = factory(Employee::class)->create();
        $employeeRepo = new EmployeeRepository($employee);
        $employeeRepo->updateEmployee([
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'status' => 1,
            'password' => 'unknown',
        ]);

        $this->assertTrue(Hash::check('unknown', $employee->password));
    }

    public function testCanUpdateEmployee(): void
    {
        $update = [
            'name' => $this->faker->name,
        ];

        /** @var Employee */
        $employee = factory(Employee::class)->create();
        $employeeRepo = new EmployeeRepository($employee);
        $updated = $employeeRepo->updateEmployee($update);

        $this->assertTrue($updated);
        $this->assertEquals($update['name'], $employee->name);
        $this->assertDatabaseHas('employees', $update);
    }

    public function testFailWhenCreatingEmployeeByEmpty(): void
    {
        $this->expectException(CreateEmployeeInvalidArgumentException::class);
        $this->expectExceptionCode(500);

        $employeeRepo = new EmployeeRepository(new Employee());
        $employeeRepo->createEmployee([]);
    }

    public function testCanCreateEmployee(): void
    {
        $data = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => 'secret',
        ];

        $employee = new EmployeeRepository(new Employee());
        $created = $employee->createEmployee($data);

        $this->assertInstanceOf(Employee::class, $created);
        $this->assertEquals($data['name'], $created->name);
        $this->assertEquals($data['email'], $created->email);
        $this->assertDatabaseHas('employees', collect($data)->except('password')->all());
    }
}
