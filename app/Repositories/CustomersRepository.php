<?php

namespace App\Repositories;

use App\Entities\Customer;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class CustomersRepository implements CustomersRepositoryInterface {

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var \Doctrine\ORM\EntityRepository
     */
    private $repository;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repository = $em->getRepository(Customer::class);
    }

    public function find(int $id): ?Customer
    {
        return $this->repository->find($id);
    }

    public function findByEmail(string $email): ?Customer
    {
        return $this->repository->findOneBy(['email' => $email]);
    }

    public function findAll(): array
    {
        return $this->repository->findAll();
    }

    public function save(Customer $product): void
    {
        $this->em->persist($product);
        $this->em->flush();
    }
}
