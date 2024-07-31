<?php

namespace tests\functional\console\workers;

use app\application\actions\RetrieveCurrencyByCodeInterface;
use app\application\actions\RetrieveSubscriptionByEmailInterface;
use app\application\actions\SendEmailInterface;
use app\application\services\LogServiceInterface;
use app\infrastructure\models\Currency;
use app\infrastructure\models\Subscription;
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
            Yii::$container->get(RetrieveCurrencyByCodeInterface::class),
            Yii::$container->get(RetrieveSubscriptionByEmailInterface::class),
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
        /** @var Subscription $subscription */
        $subscription = Subscription::find()->one();

        /** @var Currency $currency */
        $currency = Currency::find()->one();

        $body = [
            'email' => $subscription->email,
            'currency' => $currency->iso3,
        ];
        $actual = $this->getWorker()->processMessage('id', json_encode($body), 1, 1);
        $I->assertEquals(true, $actual);
    }
}
