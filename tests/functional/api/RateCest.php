<?php

namespace tests\functional\api;

use app\models\Currency;
use app\models\Subscription;
use Exception;
use tests\functional\FunctionalCest;
use tests\FunctionalTester;


class RateCest extends FunctionalCest
{

    /**
     * @param FunctionalTester $I
     * @throws Exception
     */
    public function testRateFail(FunctionalTester $I): void
    {
        Currency::deleteAll([]);
        $this->attachHeaderAcceptJson($I);
        $I->sendGet('/rate');
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(400);

        $response = json_decode($I->grabResponse(), true);
        $I->assertNotEmpty($response);
        $I->assertArrayHasKey('message', $response);
        $I->assertEquals('Invalid status value', $response['message']);
    }


    /**
     * @param FunctionalTester $I
     * @throws Exception
     */
    public function testRate(FunctionalTester $I): void
    {
        $this->attachHeaderAcceptJson($I);
        $I->sendGet('/rate');
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(200);

        $response = json_decode($I->grabResponse(), true);
        $I->assertNotEmpty($response);
        $I->assertIsNumeric($response);
    }


    /**
     * @param FunctionalTester $I
     * @throws Exception
     */
    public function testSubscribeEmptyEmail(FunctionalTester $I): void
    {
        $this->attachHeaderAcceptJson($I);
        $I->sendPost('/subscribe', []);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(422);

        $response = json_decode($I->grabResponse(), true);
        $I->assertNotEmpty($response);
        $I->assertEquals([
            ["field" => "email", "message" => "Email cannot be blank."]
        ], $response);
    }

    /**
     * @param FunctionalTester $I
     * @throws Exception
     */
    public function testSubscribeBadEmail(FunctionalTester $I): void
    {
        $this->attachHeaderAcceptJson($I);
        $I->sendPost('/subscribe', ['email' => 'email']);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(422);

        $response = json_decode($I->grabResponse(), true);
        $I->assertNotEmpty($response);
        $I->assertEquals([
            ["field" => "email", "message" => "Email is not a valid email address."]
        ], $response);
    }

    /**
     * @param FunctionalTester $I
     * @throws Exception
     */
    public function testSubscribeAlready(FunctionalTester $I): void
    {
        $subscription = Subscription::find()->one();
        $this->attachHeaderAcceptJson($I);
        $I->sendPost('/subscribe', ['email' => $subscription->email]);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(409);

        $response = json_decode($I->grabResponse(), true);
        $I->assertNotEmpty($response);
        $I->assertArrayHasKey('message', $response);
        $I->assertEquals('Already subscribed', $response['message']);
    }

    /**
     * @param FunctionalTester $I
     * @throws Exception
     */
    public function testSubscribe(FunctionalTester $I): void
    {
        $email = 'mail-' . time() . '@mail.com';
        $this->attachHeaderAcceptJson($I);
        $I->sendPost('/subscribe', ['email' => $email]);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(200);

        $response = json_decode($I->grabResponse(), true);
        $I->assertEmpty($response);

        $model = Subscription::findOne(['email' => $email]);
        $I->assertNotNull($model);
        $I->assertEquals(null, $model->last_send_at);
    }
}
