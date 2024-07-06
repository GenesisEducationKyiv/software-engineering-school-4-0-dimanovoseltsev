<?php

namespace tests\unit\app\infrastructure\repositories;

use app\application\exceptions\NotValidException;
use app\domain\dto\SearchSubscribersDto;
use app\domain\entities\Subscription;
use app\infrastructure\models\SubscriptionQuery;
use app\infrastructure\repositories\SubscriptionRepository;
use PHPUnit\Framework\MockObject\MockObject;
use tests\unit\UnitTestCase;
use yii\db\Exception;

class SubscriptionRepositoryTest extends UnitTestCase
{
    private SubscriptionRepository $repository;
    private SubscriptionQuery|MockObject $query;

    public function setUp(): void
    {
        parent::setUp();
        $this->query = $this->getSubscriptionQueryMock();
        $this->repository = new SubscriptionRepository($this->query);
    }

    /**
     * @return SubscriptionQuery|MockObject
     */
    protected function getSubscriptionQueryMock(): SubscriptionQuery|MockObject
    {
        return $this->getMockBuilder(SubscriptionQuery::class)
            ->disableOriginalConstructor()
            ->onlyMethods(
                [
                    'findByEmail',
                    'save',
                    'prepareNotSent',
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
        $model = $this->getSubscriptionModelMock();
        $email = "mai@mail.com";

        $model->email = $email;

        $this->query->expects(self::once())
            ->method('findByEmail')
            ->with($email)
            ->willReturn($model);

        $actual = $this->repository->findByEmail($email);
        self::assertInstanceOf(Subscription::class, $actual);
    }

    public function testFindByEmailNull()
    {
        $model = $this->getSubscriptionModelMock();
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
        $entity = $this->getSubscriptionEntity();
        $email = "mai@mail.com";

        $model = $this->getSubscriptionModelMock();
        $model->id = 1;
        $model->email = $email;

        $this->query->expects(self::once())
            ->method('save')
            ->with([
                'email' => $entity->getEmail()->value(),
            ])
            ->willReturn($model);

        $actual = $this->repository->save($entity);
        self::assertInstanceOf(Subscription::class, $actual);
    }

    public function testFindNotSent()
    {
        $dto = new SearchSubscribersDto(10, 20);

        $model = $this->getSubscriptionModelMock();
        $email = "mai@mail.com";
        $model->email = $email;

        $this->query->expects(self::once())
            ->method('andFilterWhere')
            ->with(['>', 'id', $dto->getLastId()])
            ->willReturn($this->query);

        $this->query->expects(self::once())
            ->method('limit')
            ->with($dto->getLimit())
            ->willReturn($this->query);

        $this->query->expects(self::once())
            ->method('orderBy')
            ->with(['id' => SORT_ASC])
            ->willReturn($this->query);

        $this->query->expects(self::once())
            ->method('clear')
            ->willReturn($this->query);

        $this->query->expects(self::once())
            ->method('all')
            ->willReturn([$model]);

        $actual = $this->repository->findNotSent($dto);
        self::assertIsArray($actual);
        self::assertArrayHasKey(0, $actual);
        self::assertInstanceOf(Subscription::class, $actual[0]);

        $actualAsArray = $actual[0]->toArray();
        self::assertArrayHasKey('email', $actualAsArray);
        self::assertEquals($email, $actualAsArray['email']);
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

