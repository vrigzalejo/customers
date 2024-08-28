<?php

namespace App\Http\Controllers;

use App\Entities\Customer;
use App\Repositories\CustomersRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CustomersController extends Controller
{

    private $customersRepo;

    public function __construct(CustomersRepositoryInterface $customersRepo)
    {
        $this->customersRepo = $customersRepo;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $minimumCount = 100;
        $customersCount = count($this->customersRepo->findAll());

        while ($customersCount < $minimumCount) {
            $humans = Http::get('https://randomuser.me/api/?nat=au');
            $humansData = $humans->json();

            $results = $humansData['results'] ?? [];

            foreach ($results as $data) {
                $customer = (new Customer())->setGender($data['gender'] ?? null)
                    ->setFullName(($data['name']['first'] ?? null) . ' ' . ($data['name']['last'] ?? null))
                    ->setEmail($data['email'] ?? null)
                    ->setUsername($data['login']['username'] ?? null)
                    ->setPassword($data['login']['password'] ?? null)
                    ->setCountry($data['location']['country'] ?? null)
                    ->setCity($data['location']['city'] ?? null)
                    ->setPhone($data['phone'] ?? null);

                if (empty($customer->getEmail())) {
                    continue;
                }

                $oldCustomer = $this->customersRepo->findByEmail($customer->getEmail());
                if ($oldCustomer) {
                    $customer = $oldCustomer->setFullName($customer->getFullName())
                        ->setUsername($customer->getUsername())
                        ->setPassword($customer->getPassword())
                        ->setCountry($customer->getCountry())
                        ->setCity($customer->getCity())
                        ->setPhone($customer->getPhone());
                }

                $this->customersRepo->save($customer);
                $customersCount = count($this->customersRepo->findAll());

                if ($customersCount === $minimumCount) {
                    return response()->json(["ayos!"]);
                }
            }
        }

        return response()->json([true]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
