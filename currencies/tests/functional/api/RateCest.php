<?php

namespace tests\functional\api;

use app\infrastructure\models\Currency;
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

}
