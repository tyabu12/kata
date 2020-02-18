<?php

namespace App\Shop\Employees\Repositories\Interfaces;

use App\Shop\Employees\Employee;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Jsdecena\Baserepo\BaseRepositoryInterface;

interface EmployeeRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * List all the employees
     *
     * @param string $order
     * @param string $sort
     * @param string[] $columns
     * @return Collection
     */
    public function listEmployees(string $order = 'id', string $sort = 'desc', array $columns = ['*']): Collection;

    /**
     * Create a employee
     *
     * @param array $params
     * @return Employee
     * @throws \InvalidArgumentException
     */
    public function createEmployee(array $params): Employee;

    /**
     * Update the employee
     *
     * @param array $params
     * @return bool
     */
    public function updateEmployee(array $params): bool;

    /**
     * Find the employee by ID
     *
     * @param int $id
     * @return Employee
     * @throws ModelNotFoundException
     */
    public function findEmployeeById(int $id): Employee;

    /**
     * Delete the employee
     *
     * @return bool
     */
    public function deleteEmployee(): bool;
}
