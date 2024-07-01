<?php

namespace app\infrastructure\repositories;

use app\application\exceptions\InvalidJsonException;
use app\application\exceptions\RemoteServiceException;
use app\domain\entities\Currency;
use app\domain\repositories\CurrencyRepositoryInterface;
use app\domain\valueObjects\Iso3;
use app\domain\valueObjects\Rate;
use app\domain\valueObjects\Timestamp;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Throwable;

class CurrencyRepository implements CurrencyRepositoryInterface
{
    /**
     * @param Client $httpClient
     */
    public function __construct(
        private readonly Client $httpClient,
    ) {
    }

    /**
     * @return Currency|null
     * @throws RemoteServiceException
     */
    public function findActual(): ?Currency
    {
        try {
            $response = $this->httpClient->get('/rate');
            $statusCode = $response->getStatusCode();
            $json = (string)$response->getBody()->getContents();

            if ($statusCode !== 200) {
                throw new RemoteServiceException(sprintf("Return %d status code. Response: %s", $statusCode, $json));
            }

            if (!json_validate($json) || empty($json)) {
                throw new InvalidJsonException();
            }

            /** @var array{iso3: string, rate: float, updatedAt:int} $body */
            $body = (array)json_decode($json, true);

            return new Currency(
                new Iso3($body['iso3']),
                new Rate($body['rate']),
                new Timestamp($body['updatedAt']),
            );
        } catch (RemoteServiceException $e) {
            throw $e;
        } catch (Throwable $e) {
            throw new RemoteServiceException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
