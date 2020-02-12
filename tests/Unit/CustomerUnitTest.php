<?php

namespace Tests\Unit;

use App\Shop\Customers\Customer;
use App\Shop\Customers\Exceptions\CreateCustomerInvalidArgumentException;
use App\Shop\Customers\Exceptions\CustomerNotFoundException;
use App\Shop\Customers\Exceptions\UpdateCustomerInvalidArgumentException;
use App\Shop\Customers\Repositories\CustomerRepository;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CustomerUnitTest extends TestCase
{
    public function testCanSoftDeleteCustomer(): void
    {
        /** @var Customer */
        $customer = factory(Customer::class)->create();
        $customerRepo = new CustomerRepository($customer);
        $delete = $customerRepo->deleteCustomer();

        $this->assertTrue($delete);
        $this->assertDatabaseHas('customers', $customer->toArray());
    }

    public function testFailWhenCustomerNotFound(): void
    {
        $this->expectException(CustomerNotFoundException::class);

        $customerRepo = new CustomerRepository(new Customer());
        $customerRepo->findCustomerById(999);
    }

    public function testCanFindCustomer(): void
    {
        $data = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => 'secret',
        ];

        $customerRepo = new CustomerRepository(new Customer());
        $created = $customerRepo->createCustomer($data);
        $found = $customerRepo->findCustomerById($created->id);

        $this->assertInstanceOf(Customer::class, $found);
        $this->assertEquals($data['name'], $found->name);
        $this->assertEquals($data['email'], $found->email);
    }

    public function testFailWhenUpdatingCustomerNameWithNull(): void
    {
        $this->expectException(UpdateCustomerInvalidArgumentException::class);

        /** @var Customer */
        $customer = factory(Customer::class)->create();
        $customerRepo = new CustomerRepository($customer);
        $customerRepo->updateCustomer(['name' => null]);
    }

    public function testCanUpdateCustomerPassword(): void
    {
        /** @var Customer */
        $customer = factory(Customer::class)->create();
        $customerRepo = new CustomerRepository($customer);
        $customerRepo->updateCustomer([
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'status' => 1,
            'password' => 'unknown',
        ]);

        $this->assertTrue(Hash::check('unknown', bcrypt($customer->password)));
    }

    public function testCanUpdateCustomer(): void
    {
        $update = [
            'name' => $this->faker->name,
        ];

        /** @var Customer */
        $customer = factory(Customer::class)->create();
        $customerRepo = new CustomerRepository($customer);
        $updated = $customerRepo->updateCustomer($update);

        $this->assertTrue($updated);
        $this->assertEquals($update['name'], $customer->name);
        $this->assertDatabaseHas('customers', $update);
    }

    public function testFailWhenCreatingCustomerByEmpty(): void
    {
        $this->expectException(CreateCustomerInvalidArgumentException::class);
        $this->expectExceptionCode(500);

        $customerRepo = new CustomerRepository(new Customer());
        $customerRepo->createCustomer([]);
    }

    public function testCanCreateCustomer(): void
    {
        $data = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => 'secret',
        ];

        $customer = new CustomerRepository(new Customer());
        $created = $customer->createCustomer($data);

        $this->assertInstanceOf(Customer::class, $created);
        $this->assertEquals($data['name'], $created->name);
        $this->assertEquals($data['email'], $created->email);
        $this->assertDatabaseHas('customers', collect($data)->except('password')->all());
    }
}
