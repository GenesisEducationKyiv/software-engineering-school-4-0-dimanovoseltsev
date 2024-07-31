<?php

namespace tests\functional\api;

use app\infrastructure\models\Customer;
use Exception;
use tests\functional\FunctionalCest;
use tests\FunctionalTester;


class CustomerCest extends FunctionalCest
{
    /**
     * @param FunctionalTester $I
     * @throws Exception
     */
    public function testCreateEmptyEmail(FunctionalTester $I): void
    {
        $this->attachHeaderAcceptJson($I);
        $I->sendPost('/customer', []);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(422);

        $response = json_decode($I->grabResponse(), true);
        $I->assertNotEmpty($response);
        $I->assertEquals([
            ["field" => "email", "message" => "Cannot be blank"]
        ], $response);
    }

    /**
     * @param FunctionalTester $I
     * @throws Exception
     */
    public function testCreateBadEmail(FunctionalTester $I): void
    {
        $this->attachHeaderAcceptJson($I);
        $I->sendPost('/customer', ['email' => 'email']);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(422);

        $response = json_decode($I->grabResponse(), true);
        $I->assertNotEmpty($response);
        $I->assertEquals([
            ["field" => "email", "message" => "Is not a valid email address"]
        ], $response);
    }

    /**
     * @param FunctionalTester $I
     * @throws Exception
     */
    public function testCreateAlready(FunctionalTester $I): void
    {
        $subscription = Customer::find()->one();
        $this->attachHeaderAcceptJson($I);
        $I->sendPost('/subscribe', ['email' => $subscription->email]);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(200);

        $response = json_decode($I->grabResponse(), true);
        $I->assertNotEmpty($response);
        $model = Customer::findOne(['email' => $subscription->email]);
        $I->assertNotNull($model);
    }

    /**
     * @param FunctionalTester $I
     * @throws Exception
     */
    public function testCreate(FunctionalTester $I): void
    {
        $email = 'mail-' . time() . '@mail.com';
        $this->attachHeaderAcceptJson($I);
        $I->sendPost('/subscribe', ['email' => $email]);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(200);

        $response = json_decode($I->grabResponse(), true);
        $I->assertEmpty($response);

        $model = Customer::findOne(['email' => $email]);
        $I->assertNotNull($model);
    }

    /**
     * @param FunctionalTester $I
     * @throws Exception
     */
    public function testDeleteEmptyEmail(FunctionalTester $I): void
    {
        $this->attachHeaderAcceptJson($I);
        $I->sendPost('/customer', []);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(422);

        $response = json_decode($I->grabResponse(), true);
        $I->assertNotEmpty($response);
        $I->assertEquals([
            ["field" => "email", "message" => "Cannot be blank"]
        ], $response);
    }

    /**
     * @param FunctionalTester $I
     * @throws Exception
     */
    public function testDeleteBadEmail(FunctionalTester $I): void
    {
        $this->attachHeaderAcceptJson($I);
        $I->sendPost('/customer', ['email' => 'email']);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(422);

        $response = json_decode($I->grabResponse(), true);
        $I->assertNotEmpty($response);
        $I->assertEquals([
            ["field" => "email", "message" => "Is not a valid email address"]
        ], $response);
    }

    /**
     * @param FunctionalTester $I
     * @throws Exception
     */
    public function testDeleteAlready(FunctionalTester $I): void
    {
        $email = 'mail-' . time() . '@mail.com';
        $this->attachHeaderAcceptJson($I);
        $I->sendPost('/customer', ['email' => $email]);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(404);

        $response = json_decode($I->grabResponse(), true);
        $I->assertNotEmpty($response);
        $I->assertArrayHasKey('message', $response);
        $I->assertEquals('Customer not exist', $response['message']);
    }

    /**
     * @param FunctionalTester $I
     * @throws Exception
     */
    public function testDelete(FunctionalTester $I): void
    {
        $subscription = Customer::find()->one();
        $this->attachHeaderAcceptJson($I);
        $I->sendPost('/customer', ['email' => $subscription->email]);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(200);

        $response = json_decode($I->grabResponse(), true);
        $I->assertEmpty($response);

        $model = Customer::findOne(['email' => $subscription->email]);
        $I->assertNull($model);
    }
}
