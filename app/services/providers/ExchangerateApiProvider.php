<?php

namespace app\services\providers;

use app\exceptions\RemoteServiceException;
use Exception;
use GuzzleHttp\Client as HttpClient;
use Psr\Http\Message\ResponseInterface;
use Throwable;
use Yii;

/**
 * Class EuropeanCentralBankProvider.
 *
 * @package app\services
 */
class ExchangerateApiProvider implements ProviderInterface
{
    /**
     * EuropeanCentralBankService constructor.
     * @param HttpClient $client
     * @param string $apiKey
     * @param string $baseCurrency
     * @param string $importCurrency
     */
    public function __construct(
        private readonly HttpClient $client,
        private readonly string $apiKey,
        private readonly string $baseCurrency = "USD",
        private readonly string $importCurrency = "USD",
    ) {
    }


    /**
     * @return array<string, float>
     * @throws RemoteServiceException
     * @see https://www.exchangerate-api.com/docs/pair-conversion-requests
     */
    public function getActualRates(): array
    {
        $url = sprintf(
            "/v6/%s/pair/%s/%s",
            $this->apiKey,
            $this->baseCurrency,
            $this->importCurrency
        );
        try {
            $response = $this->client->get($url);
            return $this->processResponse($response);
        } catch (RemoteServiceException $e) {
            throw $e;
        } catch (Throwable $e) {
            Yii::error($e);
            throw new RemoteServiceException("Service unavailable: {$e->getMessage()}");
        }
    }

    /**
     * @param ResponseInterface $response
     * @return array<string, float>
     * @throws Exception
     */
    protected function processResponse(ResponseInterface $response): array
    {
        $statusCode = $response->getStatusCode();
        if ($statusCode !== 200) {
            throw new RemoteServiceException('Status code not successfully');
        }
        $body = (array)json_decode((string)$response->getBody(), true);

        $result = $body['result'] ?? null;
        if ($result !== "success") {
            throw new RemoteServiceException('Result not success');
        }

        $conversionRate = (float)($body['conversion_rate'] ?? 0);

        if (empty($conversionRate)) {
            throw new RemoteServiceException('Bad conversion rate');
        }

        return [$this->importCurrency => round($conversionRate, 5)];
    }
}
