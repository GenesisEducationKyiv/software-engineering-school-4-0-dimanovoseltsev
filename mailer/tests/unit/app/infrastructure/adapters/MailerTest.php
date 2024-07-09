<?php

namespace tests\unit\app\infrastructure\adapters;

use app\infrastructure\adapters\Mailer;
use PHPUnit\Framework\MockObject\MockObject;
use tests\unit\UnitTestCase;
use yii\symfonymailer\Mailer as YiiMailer;
use yii\symfonymailer\Message;

class MailerTest extends UnitTestCase
{
    private Mailer $adapter;
    private readonly YiiMailer|MockObject $mailer;

    public function setUp(): void
    {
        parent::setUp();
        $this->mailer = $this->getMailerMock();
        $this->adapter = new Mailer($this->mailer);
    }

    /**
     * @return YiiMailer|MockObject
     */
    protected function getMailerMock(): YiiMailer|MockObject
    {
        return $this->getMockBuilder(YiiMailer::class)
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


    public function testSendActualRate()
    {
        $currency = $this->getCurrencyEntity();
        $subscription = $this->getSubscriptionEntity();

        $message = $this->getMessageMock();

        $this->mailer
            ->expects(self::once())
            ->method('compose')
            ->willReturn($message);


        $message
            ->expects(self::once())
            ->method('setTo')
            ->with($subscription->getEmail()->value())
            ->willReturn($message);

        $message->expects(self::once())
            ->method('setSubject')
            ->with('Actual rate')
            ->willReturn($message);

        $message->expects(self::once())
            ->method('setTextBody')
            ->with(sprintf("The current exchange rate as of %s is %f.", date("Y-m-d"), $currency->getRate()->value()))
            ->willReturn($message);

        $message->expects(self::once())
            ->method('send')
            ->willReturn(true);

        $actual = $this->adapter->sendActualRate($currency, $subscription);
    }
}
