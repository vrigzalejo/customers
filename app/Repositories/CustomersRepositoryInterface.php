<?php

namespace App\Repositories;

use App\Entities\Customer;

interface CustomersRepositoryInterface
{
    public function find(int $id): ?Customer;
    public function findByEmail(string $email): ?Customer;
    public function findAll(): array;
    public function save(Customer $customer): void;
}
