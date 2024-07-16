<?php

namespace tests\functional\console\workers;

use app\application\actions\SaveCurrencyInterface;
use app\application\services\LogServiceInterface;
use app\infrastructure\models\Currency;
use console\workers\CurrencyWorker;
use tests\functional\FunctionalCest;
use tests\FunctionalTester;
use Yii;
use yii\base\InvalidConfigException;
use yii\di\NotInstantiableException;

class CurrencyWorkerCest extends FunctionalCest
{
    /**
     * @return CurrencyWorker
     * @throws InvalidConfigException
     * @throws NotInstantiableException
     */
    private function getWorker(): CurrencyWorker
    {
        return new CurrencyWorker(
            'id',
            Yii::$app->module,
            Yii::$container->get(SaveCurrencyInterface::class),
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
        /** @var Currency $currency */
        $currency = Currency::find()->one();

        $iso3 = $currency->iso3;
        $rate = $currency->rate + 1;
        $updatedAt = time() - 1300;

        $body = [
            "eventId" => "019078f8-2704-72ed-865f-e276f6883bf5",
            "eventType" => "mail.create",
            "timestamp" => time(),
            "data" => [
                'iso3' => $iso3,
                'rate' => $rate,
                'updatedAt' => $updatedAt,
            ]
        ];
        $actual = $this->getWorker()->processMessage('id', json_encode($body), 1, 1);
        $I->assertEquals(true, $actual);


        /** @var Currency $currency */
        $currency = Currency::find()->where(['iso3' => $iso3])->one();
        $I->assertEquals((string)$iso3, (string)$currency->iso3);
        $I->assertEquals((string)$iso3, (string)$currency->iso3);
        $I->assertEquals((int)$updatedAt, (int)$currency->updated_at);
    }
}
