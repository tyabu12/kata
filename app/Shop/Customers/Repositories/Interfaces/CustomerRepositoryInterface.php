<?php

namespace App\Shop\Customers\Repositories\Interfaces;

use App\Shop\Customers\Customer;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Jsdecena\Baserepo\BaseRepositoryInterface;

interface CustomerRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * List all the customers
     *
     * @param string $order
     * @param string $sort
     * @param string[] $columns
     * @return Collection
     */
    public function listCustomers(string $order = 'id', string $sort = 'desc', array $columns = ['*']): Collection;

    /**
     * Create a customer
     *
     * @param array $params
     * @return Customer
     * @throws \InvalidArgumentException
     */
    public function createCustomer(array $params): Customer;

    /**
     * Update the customer
     *
     * @param array $params
     * @return bool
     */
    public function updateCustomer(array $params): bool;

    /**
     * Find the customer by ID
     *
     * @param int $id
     * @return Customer
     * @throws ModelNotFoundException
     */
    public function findCustomerById(int $id): Customer;

    /**
     * Delete the customer
     *
     * @return bool
     */
    public function deleteCustomer(): bool;
}
