<?php


namespace tests\functional;

use app\models\User;
use tests\FunctionalTester;
use yii\helpers\Json;

class FunctionalCest
{
    /**
     * @param FunctionalTester $I
     * @return void
     */
    protected function attachHeaderAcceptJson(FunctionalTester &$I): void
    {
        $I->haveHttpHeader('Accept', 'application/json');
    }

    /**
     * @param FunctionalTester $I
     * @param int $code
     * @param string $name
     * @param string $message
     * @return array
     */
    protected function checkExceptionResponse(FunctionalTester $I, int $code, string $name, string $message): array
    {
        $I->seeResponseCodeIs($code);
        $response = Json::decode($I->grabResponse());

        $I->assertArrayHasKey('name', $response, 'not exist name');
        $I->assertArrayHasKey('message', $response, 'not exist message');

        $I->assertEquals($name, $response['name'], 'name not eq');
        $I->assertEquals($message, $response['message'], 'message not eq');
        return $response;
    }

    /**
     * @param string $message
     * @param FunctionalTester $I
     * @return array
     */
    protected function checkResponse404(FunctionalTester $I, string $message): array
    {
        return $this->checkExceptionResponse($I, 404, 'Not Found', $message);
    }


    /**
     * @param FunctionalTester $I
     * @param array $values
     * @return array
     */
    protected function checkResponse422(FunctionalTester $I, array $values = []): array
    {
        $I->seeResponseCodeIs(422);
        $response = Json::decode($I->grabResponse());
        foreach ($values as $i => $value) {
            $I->assertArrayHasKey($i, $response, 'not exist element ' . $i);
            $I->assertArrayHasKey('field', $response[$i], 'not exist element ' . $i . '.field');
            $I->assertArrayHasKey('message', $response[$i], 'not exist element ' . $i . '.message');
            $I->assertEquals($value['field'], $response[$i]['field'], 'not eq ' . $i . '.field');
            $I->assertEquals($value['message'], $response[$i]['message'], 'not eq ' . $i . '.field');
        }

        return $response;
    }
}
