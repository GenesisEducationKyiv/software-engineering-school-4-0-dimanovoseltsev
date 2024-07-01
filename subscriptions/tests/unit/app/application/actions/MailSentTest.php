<?php

namespace tests\unit\app\application\actions;

use app\application\actions\MailSent;
use app\application\dto\MailSentDto;
use app\application\exceptions\NotExistException;
use app\application\services\SubscriptionService;
use app\domain\entities\Subscription;
use PHPUnit\Framework\MockObject\MockObject;
use tests\unit\UnitTestCase;

class MailSentTest extends UnitTestCase
{
    private MailSent $action;
    private SubscriptionService|MockObject $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = $this->getSubscriptionServiceMock();
        $this->action = new MailSent($this->service);
    }

    /**
     * @throws NotExistException
     */
    public function testExecute()
    {
        $dto = new MailSentDto("mail@mail.com", time());

        $subscription = $this->getSubscriptionEntity();
        $this->service->expects($this->once())
            ->method('getByEmailAndNotSend')
            ->with($dto->getEmail())
            ->willReturn($subscription);

        $this->service->expects($this->once())
            ->method('save')
            ->willReturn($subscription);

        $actual = $this->action->execute($dto);
        self::assertInstanceOf(Subscription::class, $actual);
    }

    /**
     * @throws NotExistException
     */
    public function testExecuteNotExist()
    {
        self::expectException(NotExistException::class);
        self::expectExceptionMessage("Subscription not exist");

        $dto = new MailSentDto("mail@mail.com", time());

        $this->service->expects($this->once())
            ->method('getByEmailAndNotSend')
            ->with($dto->getEmail())
            ->willReturn(null);

        $actual = $this->action->execute($dto);
    }
}

