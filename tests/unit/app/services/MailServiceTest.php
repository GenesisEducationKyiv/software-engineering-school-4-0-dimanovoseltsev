<?php

namespace tests\unit\app\services;

use app\services\MailService;
use PHPUnit\Framework\MockObject\MockObject;
use tests\unit\UnitTestCase;
use yii\symfonymailer\Mailer;
use yii\symfonymailer\Message;

class MailServiceTest extends UnitTestCase
{
    private MailService $service;

    private readonly Mailer|MockObject $mailer;
    private string $fromEmail = 'mail@mail.com';
    private string $fromName = 'name';

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
            ->onlyMethods([
                'compose',
            ])
            ->getMock();
    }

    /**
     * @return Message|MockObject
     */
    protected function getMessageMock(): Message|MockObject
    {
        return $this->getMockBuilder(Message::class)
            ->disableOriginalConstructor()
            ->onlyMethods([
                'setTo',
                'setSubject',
                'setTextBody',
                'send',
            ])
            ->getMock();
    }


    public function testSendUserVerify()
    {
        $currency = $this->getCurrencyModelMock(['rate' => 12.01]);
        $subscription = $this->getSubscriptionModelMock(['email' => 'mail@mail.com']);

        $message = $this->getMessageMock();

        $this->mailer
            ->expects(self::once())
            ->method('compose')
            ->willReturn($message);


        $message
            ->expects(self::once())
            ->method('setTo')
            ->with($subscription->email)
            ->willReturn($message);

        $message->expects(self::once())
            ->method('setSubject')
            ->with('Actual rate')
            ->willReturn($message);

        $message->expects(self::once())
            ->method('setTextBody')
            ->with(sprintf("The current exchange rate as of %s is %f.", date("Y-m-d"), $currency->rate))
            ->willReturn($message);

        $message->expects(self::once())
            ->method('send')
            ->willReturn(true);

        $actual = $this->service->sendActualRate($currency, $subscription);
    }
}

