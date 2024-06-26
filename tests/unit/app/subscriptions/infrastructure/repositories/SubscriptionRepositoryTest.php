<?php

namespace tests\unit\app\subscriptions\infrastructure\repositories;

use app\shared\application\exceptions\NotValidException;
use app\subscriptions\application\dto\SearchSubscribersForMailingDto;
use app\subscriptions\domain\dto\SearchSubscribersDto;
use app\subscriptions\domain\entities\Subscription;
use app\subscriptions\infrastructure\models\SubscriptionQuery;
use app\subscriptions\infrastructure\repositories\SubscriptionRepository;
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
                    'prepareNotSent',
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

    public function testFindByEmailAndNotSend()
    {
        $model = $this->getSubscriptionModelMock();
        $email = "mai@mail.com";

        $model->email = $email;

        $this->query->expects(self::once())
            ->method('clear')
            ->willReturn($this->query);

        $this->query->expects(self::once())
            ->method('prepareNotSent')
            ->with($this->breakBetweenSending)
            ->willReturn($this->query);

        $this->query->expects(self::once())
            ->method('findByEmail')
            ->with($email)
            ->willReturn($model);

        $actual = $this->repository->findByEmailAndNotSend($email);
        self::assertInstanceOf(Subscription::class, $actual);
    }

    public function testFindByEmailAndNotSendNull()
    {
        $model = $this->getSubscriptionModelMock();
        $email = "mai@mail.com";

        $model->email = $email;

        $this->query->expects(self::once())
            ->method('clear')
            ->willReturn($this->query);

        $this->query->expects(self::once())
            ->method('prepareNotSent')
            ->with($this->breakBetweenSending)
            ->willReturn($this->query);

        $this->query->expects(self::once())
            ->method('findByEmail')
            ->with($email)
            ->willReturn(null);

        $actual = $this->repository->findByEmailAndNotSend($email);
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
            ->method('prepareNotSent')
            ->with($this->breakBetweenSending)
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
}

