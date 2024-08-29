<?php

namespace App\Http\Controllers;

use App\Repositories\CustomersRepositoryInterface;

class CustomersController extends Controller
{

    private $customersRepo;

    public function __construct(CustomersRepositoryInterface $customersRepo)
    {
        $this->customersRepo = $customersRepo;
    }

    public function index()
    {
        $data = [];
        foreach ($this->customersRepo->findAll() as $customer) {
            $data[] = [
                'full_name' => $customer->getFullName(),
                'email' => $customer->getEmail(),
                'country' => $customer->getCountry()
            ];
        }

        return response()->json([
            'success' => true,
            'results' => $data
        ]);
    }

    public function show(string $id)
    {
        $customer = $this->customersRepo->find((int) $id);
        if (! $customer) {
            return response()->json([
                'success' => false,
                'message' => 'Not found.'
            ], 404);
        }

        $data= [
            'full_name' => $customer->getFullName(),
            'email' => $customer->getEmail(),
            'country' => $customer->getCountry(),
            'gender' => $customer->getGender(),
            'city' => $customer->getCity(),
            'phone' => $customer->getPhone()
        ];

        return response()->json([
            'success' => true,
            'result' => $data
        ]);
    }
}
