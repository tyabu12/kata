<?php

namespace App\Shop\Customers\Repositories;

use App\Shop\Customers\Customer;
use App\Shop\Customers\Repositories\Interfaces\CustomerRepositoryInterface;
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
     * List all the Customers
     *
     * @param string $order
     * @param string $sort
     * @param string[] $except
     * @return \Illuminate\Support\Collection
     */
    public function listCustomers(string $order = 'id', string $sort = 'desc', $except = []): Collection
    {
        return collect($this->model->orderBy($order, $sort)->get())->except($except);
    }

    /**
     * Create a Customer
     *
     * @param array $params
     * @return Customer
     * @throws \InvalidArgumentException
     */
    public function createCustomer(array $params): Customer
    {
        try {
        	return Customer::create($params);
        } catch (QueryException $e) {
            throw new \InvalidArgumentException($e->getMessage());
        }
    }

    /**
     * Update the customer
     *
     * @param array $params
     * @return Customer
     */
    public function updateCustomer(array $params): Customer
    {
        $customer = $this->findCustomerById($this->model->id);
        $customer->update($params);
        return $customer;
    }

    /**
     * Find the customer
     *
     * @param int $id
     * @return Customer
     * @throws ModelNotFoundException
     */
    public function findCustomerById(int $id): Customer
    {
        try {
            return $this->findOneOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException($e->getMessage());
        }
    }

    /**
     * Delete the customer
     *
     * @return bool
     */
    public function deleteCustomer(): bool
    {
        return (bool) $this->model->delete();
    }
}
