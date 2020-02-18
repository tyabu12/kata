<?php

namespace App\Shop\Employees\Repositories;

use App\Shop\Employees\Employee;
use App\Shop\Employees\Exceptions\CreateEmployeeInvalidArgumentException;
use App\Shop\Employees\Exceptions\EmployeeNotFoundException;
use App\Shop\Employees\Exceptions\UpdateEmployeeInvalidArgumentException;
use App\Shop\Employees\Repositories\Interfaces\EmployeeRepositoryInterface;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Jsdecena\Baserepo\BaseRepository;
use \InvalidArgumentException;

class EmployeeRepository extends BaseRepository implements EmployeeRepositoryInterface
{
    /**
     * EmployeeRepository constructor.
     *
     * @param Employee $employee
     */
    public function __construct(Employee $employee)
    {
        parent::__construct($employee);
        $this->model = $employee;
    }

    /**
     * List all the employees
     *
     * @param string $order
     * @param string $sort
     * @param string[] $columns
     * @return Collection
     */
    public function listEmployees(string $order = 'id', string $sort = 'desc', array $columns = ['*']): Collection
    {
        return $this->all($columns, $order, $sort);
    }

    /**
     * Create a employee
     *
     * @param array $params
     * @return Employee
     * @throws CreateEmployeeInvalidArgumentException
     */
    public function createEmployee(array $params): Employee
    {
        try {
            $params['password'] = Hash::make($params['password']);
            return $this->create($params);
        } catch (Exception $e) {
            throw new CreateEmployeeInvalidArgumentException($e->getMessage(), 500, $e);
        }
    }

    /**
     * Update the employee
     *
     * @param array $params
     * @return bool
     * @throws UpdateEmployeeInvalidArgumentException
     */
    public function updateEmployee(array $params): bool
    {
        try {
            if (isset($params['password'])) {
                $params['password'] = Hash::make($params['password']);
            }
            return $this->update($params);
        } catch (Exception $e) {
            throw new UpdateEmployeeInvalidArgumentException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Find the employee by ID
     *
     * @param int $id
     * @return Employee
     * @throws EmployeeNotFoundException
     */
    public function findEmployeeById(int $id): Employee
    {
        try {
            return $this->findOneOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw new EmployeeNotFoundException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Delete the employee
     *
     * @return bool
     */
    public function deleteEmployee(): bool
    {
        return (bool) $this->delete();
    }
}
