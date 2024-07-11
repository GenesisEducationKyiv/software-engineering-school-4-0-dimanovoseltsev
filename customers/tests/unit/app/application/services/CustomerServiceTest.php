<?php

namespace tests\unit\app\application\services;


use app\application\dto\SearchSubscribersForMailingDto;
use app\application\services\CustomerService;
use app\domain\dto\SearchSubscribersDto;
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
                ['findByEmail', 'save', 'deleteByEmail']
            )
            ->getMock();
    }

    public function testGetByEmail()
    {
        $email = "mail@mail.com";

        $entity = $this->getCustomerEntity();
        $this->repository->expects($this->once())
            ->method('findByEmail')
            ->with($email)
            ->willReturn($entity);

        $actual = $this->service->getByEmail($email);
        self::assertInstanceOf(Customer::class, $actual);
    }

    public function testGetByEmailNull()
    {
        $email = "mail@mail.com";

        $this->repository->expects($this->once())
            ->method('findByEmail')
            ->with($email)
            ->willReturn(null);

        $actual = $this->service->getByEmail($email);
        self::assertNull($actual);
    }

    public function testSave()
    {
        $entity = $this->getCustomerEntity();
        $this->repository->expects($this->once())
            ->method('save')
            ->with($entity)
            ->willReturn($entity);

        $actual = $this->service->save($entity);
        self::assertInstanceOf(Customer::class, $actual);
    }

    public function testDelete()
    {
        $email = "mail@mail.com";
        $this->repository->expects($this->once())
            ->method('deleteByEmail')
            ->with($email)
            ->willReturn(1);

        $actual = $this->service->delete($email);
        self::assertEquals(1, $actual);
    }
}

