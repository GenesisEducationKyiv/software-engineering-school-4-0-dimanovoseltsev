<?php

namespace tests\unit\app\infrastructure\repositories;

use app\application\exceptions\NotValidException;
use app\domain\dto\SearchSubscribersDto;
use app\domain\entities\Customer;
use app\infrastructure\models\CustomerQuery;
use app\infrastructure\repositories\CustomerRepository;
use PHPUnit\Framework\MockObject\MockObject;
use tests\unit\UnitTestCase;
use yii\db\Exception;

class CustomerRepositoryTest extends UnitTestCase
{
    private CustomerRepository $repository;
    private CustomerQuery|MockObject $query;

    public function setUp(): void
    {
        parent::setUp();
        $this->query = $this->getCustomerQueryMock();
        $this->repository = new CustomerRepository($this->query);
    }

    /**
     * @return CustomerQuery|MockObject
     */
    protected function getCustomerQueryMock(): CustomerQuery|MockObject
    {
        return $this->getMockBuilder(CustomerQuery::class)
            ->disableOriginalConstructor()
            ->onlyMethods(
                [
                    'findByEmail',
                    'save',
                    'clear',
                    'andFilterWhere',
                    'limit',
                    'orderBy',
                    'all',
                    'deleteByEmail',
                ]
            )
            ->getMock();
    }

    public function testFindByEmail()
    {
        $model = $this->getCustomerModelMock();
        $email = "mai@mail.com";

        $model->email = $email;

        $this->query->expects(self::once())
            ->method('findByEmail')
            ->with($email)
            ->willReturn($model);

        $actual = $this->repository->findByEmail($email);
        self::assertInstanceOf(Customer::class, $actual);
    }

    public function testFindByEmailNull()
    {
        $model = $this->getCustomerModelMock();
        $email = "mai@mail.com";

        $model->email = $email;

        $this->query->expects(self::once())
            ->method('findByEmail')
            ->with($email)
            ->willReturn(null);

        $actual = $this->repository->findByEmail($email);
        self::assertNull($actual);
    }

    /**
     * @throws NotValidException
     * @throws Exception
     */
    public function testSave()
    {
        $entity = $this->getCustomerEntity();
        $email = "mai@mail.com";

        $model = $this->getCustomerModelMock();
        $model->id = 1;
        $model->email = $email;

        $this->query->expects(self::once())
            ->method('save')
            ->with([
                'id' => $entity->getId()->value(),
                'email' => $entity->getEmail()->value(),
            ])
            ->willReturn($model);

        $actual = $this->repository->save($entity);
        self::assertInstanceOf(Customer::class, $actual);
    }

    public function testDeleteByEmail()
    {
        $email = "mai@mail.com";

        $this->query->expects(self::once())
            ->method('deleteByEmail')
            ->with($email)
            ->willReturn(1);

        $actual = $this->repository->deleteByEmail($email);
        self::assertEquals(1, $actual);
    }
}

