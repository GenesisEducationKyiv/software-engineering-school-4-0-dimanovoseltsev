<?php

namespace tests\unit\app\application\actions;

use app\application\actions\Subscribe;
use app\application\dto\CreateSubscriptionDto;
use app\application\exceptions\AlreadyException;
use app\application\exceptions\BadRequestException;
use app\application\exceptions\InvalidCallException;
use app\application\exceptions\NotValidException;
use app\application\forms\SubscribeForm;
use app\application\services\SubscriptionService;
use app\domain\entities\Subscription;
use app\infrastructure\adapters\EventBusRabbitMQ;
use app\infrastructure\services\TemporalActivityService;
use app\infrastructure\services\YiiLogger;
use PHPUnit\Framework\MockObject\MockObject;
use tests\unit\UnitTestCase;

class SubscribeTest extends UnitTestCase
{
    private Subscribe $action;
    private SubscriptionService|MockObject $service;
    private TemporalActivityService|MockObject $activityService;
    private YiiLogger|MockObject $logger;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = $this->getSubscriptionServiceMock();
        $this->activityService = $this->getActivityServiceMock();
        $this->logger = $this->getLogServiceMock();
        $this->action = new Subscribe($this->service, $this->activityService, $this->logger);
    }

    /**
     * @return TemporalActivityService|MockObject
     */
    protected function getActivityServiceMock(): TemporalActivityService|MockObject
    {
        return $this->getMockBuilder(TemporalActivityService::class)
            ->disableOriginalConstructor()
            ->onlyMethods([
                'subscribe',
            ])
            ->getMock();
    }


    /**
     * @throws NotValidException
     * @throws AlreadyException
     */
    public function testExecute()
    {
        $form = new SubscribeForm("mail@mail.com");
        $entity = $this->getSubscriptionEntity();
        $this->service->expects($this->once())
            ->method('getByEmail')
            ->with($form->getEmail())
            ->willReturn(null);

        $this->activityService->expects($this->once())
            ->method('subscribe')
            ->with($form->getEmail())
            ->willReturn([
                'subscription' => $entity->toArray()
            ]);

        $actual = $this->action->execute($form);
        self::assertInstanceOf(Subscription::class, $actual);
    }

    /**
     * @throws AlreadyException|NotValidException
     */
    public function testExecuteAlreadyExists()
    {
        self::expectException(AlreadyException::class);
        self::expectExceptionMessage("Already subscribed");

        $form = new SubscribeForm("mail@mail.com");
        $entity = $this->getSubscriptionEntity();
        $this->service->expects($this->once())
            ->method('getByEmail')
            ->with($form->getEmail())
            ->willReturn($entity);

        $actual = $this->action->execute($form);
    }

    /**
     * @throws AlreadyException|NotValidException
     */
    public function testExecuteNotValid()
    {
        self::expectException(NotValidException::class);

        $form = new SubscribeForm("mail");

        $actual = $this->action->execute($form);
    }


    /**
     * @throws NotValidException
     * @throws AlreadyException
     */
    public function testExecuteInvalidCallException()
    {
        self::expectException(InvalidCallException::class);
        $form = new SubscribeForm("mail@mail.com");
        $entity = $this->getSubscriptionEntity();
        $this->service->expects($this->once())
            ->method('getByEmail')
            ->with($form->getEmail())
            ->willReturn(null);

        $this->activityService->expects($this->once())
            ->method('subscribe')
            ->with($form->getEmail())
            ->willThrowException(new InvalidCallException('Error'));

        $this->logger->expects(self::once())
            ->method('debug');

        $actual = $this->action->execute($form);
    }

    /**
     * @throws NotValidException
     * @throws AlreadyException
     */
    public function testExecuteBadRequestException()
    {
        self::expectException(BadRequestException::class);

        $form = new SubscribeForm("mail@mail.com");
        $entity = $this->getSubscriptionEntity();
        $this->service->expects($this->once())
            ->method('getByEmail')
            ->with($form->getEmail())
            ->willReturn(null);

        $this->activityService->expects($this->once())
            ->method('subscribe')
            ->with($form->getEmail())
            ->willThrowException(new BadRequestException('Error'));

        $actual = $this->action->execute($form);
    }
}

