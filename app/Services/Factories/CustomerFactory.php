<?php

namespace App\Services\Factories;

use App\Entities\Customer;

class CustomerFactory
{
    public static function createCustomer(array $data): Customer
    {
        return (new Customer())
            ->setGender($data['gender'] ?? '')
            ->setFullName(($data['name']['first'] ?? '') . ' ' . ($data['name']['last'] ?? ''))
            ->setEmail($data['email'] ?? '')
            ->setUsername($data['login']['username'] ?? '')
            ->setPassword($data['login']['password'] ?? '')
            ->setCountry($data['location']['country'] ?? '')
            ->setCity($data['location']['city'] ?? '')
            ->setPhone($data['phone'] ?? '');
    }

    public static function updateCustomer(Customer $customer, array $data)
    {
        return $customer->setGender($data['gender'] ?? '')
            ->setFullName(($data['name']['first'] ?? '') . ' ' . ($data['name']['last'] ?? ''))
            ->setUsername($data['login']['username'] ?? '')
            ->setPassword($data['login']['password'] ?? '')
            ->setCountry($data['location']['country'] ?? '')
            ->setCity($data['location']['city'] ?? '')
            ->setPhone($data['phone'] ?? '');
    }
}
