<?php

namespace tests\functional\console\workers;

use app\application\actions\MailSentInterface;
use app\application\actions\SendEmailInterface;
use app\application\services\LogServiceInterface;
use app\application\services\SubscriptionServiceInterface;
use console\workers\MailSentWorker;
use console\workers\MailWorker;
use tests\functional\FunctionalCest;
use tests\FunctionalTester;
use Yii;
use yii\base\InvalidConfigException;
use yii\di\NotInstantiableException;

class MailSentWorkerCest extends FunctionalCest
{
    /**
     * @return MailWorker
     * @throws InvalidConfigException
     * @throws NotInstantiableException
     */
    private function getWorker(): MailWorker
    {
        return new MailWorker(
            'id',
            Yii::$app->module,
            Yii::$container->get(SendEmailInterface::class),
            Yii::$container->get(LogServiceInterface::class),
            []
        );
    }

    /**
     * @param FunctionalTester $I
     * @return void
     * @throws InvalidConfigException
     * @throws NotInstantiableException
     */
    public function testBadParams(FunctionalTester $I): void
    {
        $body = [];
        $actual = $this->getWorker()->processMessage('id', json_encode($body), 1, 1);
        $I->assertEquals(true, $actual);
    }

    /**
     * @param FunctionalTester $I
     * @return void
     * @throws InvalidConfigException
     * @throws NotInstantiableException
     */
    public function testProcessMessage(FunctionalTester $I): void
    {
        $email = 'user1@gmail.com';
        $timestamp = 1720016496;
        $body = [
            "eventId" => "019078f8-2704-72ed-865f-e276f6883bf5",
            "eventType" => "mail.sent",
            "timestamp" => $timestamp,
            "data" => [
                "email" => $email,
                "currency" => [
                    'iso3' => 'UAH',
                    'rate' => 5.1,
                    'updatedAt' => $timestamp,
                ]
            ]
        ];
        $actual = $this->getWorker()->processMessage('id', json_encode($body), 1, 1);
        $I->assertEquals(true, $actual);
    }
}
