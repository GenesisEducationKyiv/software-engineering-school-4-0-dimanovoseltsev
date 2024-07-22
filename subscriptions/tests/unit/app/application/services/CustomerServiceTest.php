<?php

namespace tests\unit\app\application\services;

use app\application\services\CustomerService;
use app\domain\entities\Customer;
use app\infrastructure\repositories\CustomerRepository;
use PHPUnit\Framework\MockObject\MockObject;
use tests\unit\UnitTestCase;

class CustomerServiceTest extends UnitTestCase
{
    private CustomerService $service;
    private CustomerRepository|MockObject $repository;

    public function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->getCustomerRepositoryMock();
        $this->service = new CustomerService($this->repository);
    }

    /**
     * @return CustomerRepository|MockObject
     */
    protected function getCustomerRepositoryMock(): CustomerRepository|MockObject
    {
        return $this->getMockBuilder(CustomerRepository::class)
            ->disableOriginalConstructor()
            ->onlyMethods(
                ['create', 'delete']
            )
            ->getMock();
    }


    public function testCreate()
    {
        $email = "mail@mail.com";

        $entity = $this->getCustomerEntity();
        $this->repository->expects($this->once())
            ->method('create')
            ->with($email)
            ->willReturn($entity);

        $actual = $this->service->create($email);
        self::assertInstanceOf(Customer::class, $actual);
    }

    public function testDelete()
    {
        $email = "mail@mail.com";
        $this->repository->expects($this->once())
            ->method('delete')
            ->with($email)
            ->willReturn(1);

        $actual = $this->service->delete($email);
        self::assertEquals(1, $actual);
    }
}

