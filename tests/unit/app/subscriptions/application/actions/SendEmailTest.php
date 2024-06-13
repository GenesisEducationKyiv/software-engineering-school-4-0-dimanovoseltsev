<?php

namespace tests\unit\app\subscriptions\application\actions;

use app\shared\application\exceptions\NotExistException;
use app\subscriptions\application\actions\SendEmail;
use app\subscriptions\application\dto\SendEmailDto;
use app\subscriptions\application\services\MailService;
use app\subscriptions\application\services\SubscriptionService;
use PHPUnit\Framework\MockObject\MockObject;
use tests\unit\UnitTestCase;

class SendEmailTest extends UnitTestCase
{
    private SendEmail $action;
    private SubscriptionService|MockObject $service;
    private MailService|MockObject $mailService;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = $this->getSubscriptionServiceMock();
        $this->mailService = $this->getMailServiceMock();
        $this->action = new SendEmail($this->service, $this->mailService);
    }

    /**
     * @return MailService|MockObject
     */
    protected function getMailServiceMock(): MailService|MockObject
    {
        return $this->getMockBuilder(MailService::class)
            ->disableOriginalConstructor()
            ->onlyMethods(
                [
                    'sendMail',
                ]
            )
            ->getMock();
    }

    /**
     * @throws NotExistException
     */
    public function testExecute()
    {
        $currency = $this->getCurrencyEntity();
        $subscription = $this->getSubscriptionEntity();

        $dto = new SendEmailDto("mail@mail.com", time());

        $this->service->expects($this->once())
            ->method('getByEmailAndNotSend')
            ->with($dto->getEmail())
            ->willReturn($subscription);

        $this->mailService->expects(self::once())
            ->method('sendMail')
            ->with($currency, $subscription)
            ->willReturn(true);

        $this->service->expects(self::once())
            ->method('save')
            ->willReturn($subscription);

        $actual = $this->action->execute($currency, $dto);
        self::assertTrue($actual);
    }

    /**
     * @throws NotExistException
     */
    public function testExecuteNotExist()
    {
        self::expectException(NotExistException::class);
        self::expectExceptionMessage("Subscription not exit");

        $currency = $this->getCurrencyEntity();
        $dto = new SendEmailDto("mail@mail.com", time());
        $this->service->expects($this->once())
            ->method('getByEmailAndNotSend')
            ->with($dto->getEmail())
            ->willReturn(null);

        $actual = $this->action->execute($currency, $dto);
    }
}

