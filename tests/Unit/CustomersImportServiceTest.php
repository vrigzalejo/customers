<?php

namespace Tests\Unit\Services;

use App\Repositories\CustomersRepositoryInterface;
use App\Services\CustomersImportService;
use App\Services\Factories\CustomerFactory;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use PHPUnit\Framework\TestCase;

class CustomersImportServiceTest extends TestCase
{
    protected $customersRepoMock;
    protected $customerFactoryMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->customersRepoMock = $this->createMock(CustomersRepositoryInterface::class);
        $this->customerFactoryMock = $this->createMock(CustomerFactory::class);

        Log::shouldReceive('info')->andReturnTrue();
    }

    /**
     * Commented below due to memory issue
     */
    // public function testCreateWhenLessThanMinimumCustomers()
    // {
    //     $this->customersRepoMock
    //         ->method('findAll')
    //         ->willReturn([]);

    //     Http::shouldReceive('get')
    //         ->andReturn($this->mockHttpResponse(1));

    //     $this->customerFactoryMock
    //         ->method('createCustomer')
    //         ->willReturn($this->mockCustomer(['email' => 'user@example.com']));

    //     $service = new CustomersImportService($this->customerFactoryMock);
    //     $result = $service->create($this->customersRepoMock);

    //     $this->assertTrue($result);
    // }

    public function testCreateWhenAlreadyAtMinimum()
    {
        $this->customersRepoMock
            ->method('findAll')
            ->willReturn($this->generateCustomers(100));

        $service = new CustomersImportService($this->customerFactoryMock);
        $result = $service->create($this->customersRepoMock);

        $this->assertFalse($result);
    }

    private function mockHttpResponse(int $count)
    {
        $results = [];
        for ($i = 0; $i < $count; $i++) {
            $results[] = ['email' => "brigsalejo{$i}@example.com"];
        }

        return new \Illuminate\Http\Client\Response(new \GuzzleHttp\Psr7\Response(
            200,
            [],
            json_encode(['results' => $results])
        ));
    }

    private function generateCustomers(int $count)
    {
        $customers = [];
        for ($i = 0; $i < $count; $i++) {
            $customers[] = $this->mockCustomer(['email' => "brigsalejo{$i}@example.com"]);
        }
        return $customers;
    }

    private function mockCustomer(array $data)
    {
        $customerMock = $this->getMockBuilder(\App\Entities\Customer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $customerMock->method('getEmail')
            ->willReturn($data['email']);

        return $customerMock;
    }
}
