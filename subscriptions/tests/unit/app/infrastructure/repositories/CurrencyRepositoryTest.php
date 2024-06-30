<?php

namespace tests\unit\app\infrastructure\repositories;

use app\application\enums\CurrencyIso;
use app\application\exceptions\InvalidJsonException;
use app\application\exceptions\RemoteServiceException;
use app\domain\entities\Currency;
use app\infrastructure\repositories\CurrencyRepository;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\MockObject\MockObject;
use tests\unit\UnitTestCase;

class CurrencyRepositoryTest extends UnitTestCase
{
    private CurrencyRepository $repository;
    private Client|MockObject $httpClient;

    public function setUp(): void
    {
        parent::setUp();
        $this->httpClient = $this->getHttpClientMock();
        $this->repository = new CurrencyRepository($this->httpClient);
    }

    /**
     * @return Client|MockObject
     */
    protected function getHttpClientMock(): Client|MockObject
    {
        return $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->onlyMethods(
                [
                    'get',
                ]
            )
            ->getMock();
    }

    public function testFindActual()
    {
        $responseBody = ["iso3" => CurrencyIso::UAH->value, "rate" => 5, "updatedAt" => time()];

        $this->httpClient->expects(self::once())
            ->method('get')
            ->with('/rate')
            ->willReturn(new Response(200, [], json_encode($responseBody)));

        $actual = $this->repository->findActual();
        self::assertInstanceOf(Currency::class, $actual);

        self::assertEquals($responseBody['iso3'], $actual->getIso3()->value());
        self::assertEquals($responseBody['rate'], $actual->getRate()->value());
        self::assertEquals($responseBody['updatedAt'], $actual->getUpdatedAt()->value());
    }

    public function testFindActualNot200()
    {
        self::expectException(RemoteServiceException::class);
        self::expectExceptionMessage("Return 500 status code");

        $responseBody = ["error" => "error message"];

        $this->httpClient->expects(self::once())
            ->method('get')
            ->with('/rate')
            ->willReturn(new Response(500, [], json_encode($responseBody)));

        $actual = $this->repository->findActual();
    }

    public function testFindActualNotValidJson()
    {
        self::expectException(RemoteServiceException::class);

        $this->httpClient->expects(self::once())
            ->method('get')
            ->with('/rate')
            ->willReturn(new Response(200, [], 'error message'));

        $actual = $this->repository->findActual();
    }
}

