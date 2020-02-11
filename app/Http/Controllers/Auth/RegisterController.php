<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Shop\Customers\Customer;
use App\Shop\Customers\Repositories\Interfaces\CustomerRepositoryInterface;
use App\Shop\Customers\Requests\CreateCustomerRequest;
use App\Shop\Customers\Requests\RegisterCustomerRequest;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/accounts';

    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepo;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(CustomerRepositoryInterface $customerRepo)
    {
        $this->middleware('guest');
        $this->customerRepo = $customerRepo;
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return Customer
     */
    protected function create(array $data): Customer
    {
        return $this->customerRepo->createCustomer($data);
    }

    /**
     * @param RegisterCustomerRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(RegisterCustomerRequest $request): \Illuminate\Http\RedirectResponse
    {
        $customer = $this->create($request->except('_method', '_token'));
        Auth::login($customer);

        return redirect()->route('accounts');
    }
}
