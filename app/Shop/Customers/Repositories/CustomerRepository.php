<?php

namespace App\Shop\Customers\Repositories;

use App\Shop\Customers\Customer;
use App\Shop\Customers\Repositories\Interfaces\CustomerRepositoryInterface;
use App\Shop\Customers\Exceptions\CreateCustomerInvalidArgumentException;
use App\Shop\Customers\Exceptions\CustomerNotFoundException;
use App\Shop\Customers\Exceptions\UpdateCustomerInvalidArgumentException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Jsdecena\Baserepo\BaseRepository;

class CustomerRepository extends BaseRepository implements CustomerRepositoryInterface
{
	/**
     * CustomerRepository constructor.
     *
     * @param Customer $customer
     */
    public function __construct(Customer $customer)
    {
        parent::__construct($customer);
        $this->model = $customer;
    }

    /**
     * List all the customers
     *
     * @param string $order
     * @param string $sort
     * @param string[] $columns
     * @return Collection
     */
    public function listCustomers(string $order = 'id', string $sort = 'desc', array $columns = ['*']): Collection
    {
        return $this->all($columns, $order, $sort);
    }

    /**
     * Create a customer
     *
     * @param array $params
     * @return Customer
     * @throws CreateCustomerInvalidArgumentException
     */
    public function createCustomer(array $params): Customer
    {
        try {
            $data = collect($params)->except('password')->all();

            $customer = new Customer($data);
            if (isset($params['password'])) {
                $customer->password = bcrypt($params['password']);
            }
            $customer->saveOrFail();

            return $customer;
        } catch (QueryException $e) {
            throw new CreateCustomerInvalidArgumentException($e->getMessage(), 500, $e);
        }
    }

    /**
     * Update the customer
     *
     * @param array $params
     * @return bool
     * @throws UpdateCustomerInvalidArgumentException
     */
    public function updateCustomer(array $params): bool
    {
        try {
            return $this->update($params);
        } catch (QueryException $e) {
            throw new UpdateCustomerInvalidArgumentException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Find the customer
     *
     * @param int $id
     * @return Customer
     * @throws CustomerNotFoundException
     */
    public function findCustomerById(int $id): Customer
    {
        try {
            return $this->findOneOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw new CustomerNotFoundException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Delete the customer
     *
     * @return bool
     * @throws \Exception
     */
    public function deleteCustomer(): bool
    {
        return (bool) $this->delete();
    }
}
