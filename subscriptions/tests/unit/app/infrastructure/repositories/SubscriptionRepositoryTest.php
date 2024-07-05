<?php

namespace tests\unit\app\infrastructure\repositories;

use app\application\exceptions\NotValidException;
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
    private int $breakBetweenSending = 10;

    public function setUp(): void
    {
        parent::setUp();
        $this->query = $this->getSubscriptionQueryMock();
        $this->repository = new SubscriptionRepository($this->query, $this->breakBetweenSending);
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
                    'clear',
                    'andFilterWhere',
                    'limit',
                    'orderBy',
                    'all',
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
                'id' => $entity->getId()->value(),
                'email' => $entity->getEmail()->value(),
                'created_at' => $entity->getCreatedAt()->value(),
                'updated_at' => $entity->getUpdatedAt()->value(),
                'last_send_at' => $entity->getLastSendAt()->value(),
            ])
            ->willReturn($model);

        $actual = $this->repository->save($entity);
        self::assertInstanceOf(Subscription::class, $actual);
    }
}

