<?php

namespace tests\unit\app\subscriptions\application\services;

use app\subscriptions\application\services\MailService;
use app\subscriptions\infrastructure\adapters\Mailer;
use PHPUnit\Framework\MockObject\MockObject;
use tests\unit\UnitTestCase;

class MailServiceTest extends UnitTestCase
{
    private MailService $service;
    private Mailer|MockObject $mailer;

    public function setUp(): void
    {
        parent::setUp();
        $this->mailer = $this->getMailerMock();
        $this->service = new MailService($this->mailer);
    }

    /**
     * @return Mailer|MockObject
     */
    protected function getMailerMock(): Mailer|MockObject
    {
        return $this->getMockBuilder(Mailer::class)
            ->disableOriginalConstructor()
            ->onlyMethods(
                ['sendActualRate']
            )
            ->getMock();
    }

    public function testSendMail()
    {
        $currency = $this->getCurrencyEntity();
        $subscription = $this->getSubscriptionEntity();

        $this->mailer->expects($this->once())
            ->method('sendActualRate')
            ->with($currency, $subscription)
            ->willReturn(true);

        $actual = $this->service->sendMail($currency, $subscription);
        self::assertTrue($actual);
    }

}

