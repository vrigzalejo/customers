<?php

namespace App\Services;

use App\Repositories\CustomersRepositoryInterface;
use App\Services\Factories\CustomerFactory;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CustomersImportService
{
    const DEFAULT_URL = 'https://randomuser.me/api/?nat=au';

    const DEFAULT_MIN_COUNT = 100;

    public static function create(CustomersRepositoryInterface $customersRepo)
    {
        while (count($customersRepo->findAll()) < static::DEFAULT_MIN_COUNT) {
            $humans = Http::get(static::DEFAULT_URL);
            $humansData = $humans->json();

            $results = $humansData['results'] ?? [];

            foreach ($results as $data) {
                $customer = CustomerFactory::createCustomer($data);
                $message = 'Created';
                if (empty($customer->getEmail())) {
                    continue;
                }

                $oldCustomer = $customersRepo->findByEmail($customer->getEmail());
                if ($oldCustomer) {
                    $message = 'Updated';
                    $customer = CustomerFactory::updateCustomer($oldCustomer, $data);
                }

                $customersRepo->save($customer);
                Log::info("{$message} {$customer->getEmail()}");

                if (count($customersRepo->findAll()) === static::DEFAULT_MIN_COUNT) {
                    Log::info('Already reached minimum of ' . static::DEFAULT_MIN_COUNT);
                    return true;
                }
            }
        }

        Log::info('Nothing to import.');
        return false;
    }
}
