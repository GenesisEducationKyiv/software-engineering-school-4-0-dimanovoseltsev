<?php

namespace tests\functional\console\workers;

use app\application\actions\DeleteSubscriptionInterface;
use app\application\actions\SaveSubscriptionInterface;
use app\application\services\LogServiceInterface;
use app\infrastructure\models\Subscription;
use console\workers\SubscriptionWorker;
use tests\functional\FunctionalCest;
use tests\FunctionalTester;
use Yii;
use yii\base\InvalidConfigException;
use yii\di\NotInstantiableException;

class SubscriptionWorkerCest extends FunctionalCest
{
    /**
     * @return SubscriptionWorker
     * @throws InvalidConfigException
     * @throws NotInstantiableException
     */
    private function getWorker(): SubscriptionWorker
    {
        return new SubscriptionWorker(
            'id',
            Yii::$app->module,
            Yii::$container->get(SaveSubscriptionInterface::class),
            Yii::$container->get(DeleteSubscriptionInterface::class),
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
    public function testProcessMessageCreate(FunctionalTester $I): void
    {
        $email = 'mail-' . time() . '@mail.com';

        $body = [
            "eventId" => "019078f8-2704-72ed-865f-e276f6883bf5",
            "eventType" => "subscription.create",
            "timestamp" => time(),
            "data" => [
                'email' => $email,
            ]
        ];
        $actual = $this->getWorker()->processMessage('id', json_encode($body), 1, 1);
        $I->assertEquals(true, $actual);

        /** @var Subscription $subscription */
        $subscription = Subscription::find()->where(['email' => $email])->one();
        $I->assertNotNull($subscription);
    }

    /**
     * @param FunctionalTester $I
     * @return void
     * @throws InvalidConfigException
     * @throws NotInstantiableException
     */
    public function testProcessMessageDelete(FunctionalTester $I): void
    {
        /** @var Subscription $subscription */
        $subscription = Subscription::find()->one();

        $email = $subscription->email;

        $body = [
            "eventId" => "019078f8-2704-72ed-865f-e276f6883bf5",
            "eventType" => "subscription.delete",
            "timestamp" => time(),
            "data" => [
                'email' => $email,
            ]
        ];
        $actual = $this->getWorker()->processMessage('id', json_encode($body), 1, 1);
        $I->assertEquals(true, $actual);

        /** @var Subscription $subscription */
        $subscription = Subscription::find()->where(['email' => $email])->one();
        $I->assertNull($subscription);
    }
}
