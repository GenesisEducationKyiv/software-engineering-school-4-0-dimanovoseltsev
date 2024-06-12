<?php

namespace app\currencies\infrastructure\providers;

use app\currencies\application\dto\CurrencyProviderDto;
use app\currencies\application\providers\ProviderInterface;
use app\shared\application\exceptions\RemoteServiceException;
use Exception;
use GuzzleHttp\Client as HttpClient;
use PHPUnit\Util\InvalidJsonException;
use Psr\Http\Message\ResponseInterface;
use Throwable;
use Yii;

class EuropeanCentralBankProvider implements ProviderInterface
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
     * @return CurrencyProviderDto[]
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
            throw new RemoteServiceException("Service unavailable: {$e->getMessage()}", previous: $e);
        }
    }

    /**
     * @param ResponseInterface $response
     * @return CurrencyProviderDto[]
     * @throws Exception
     */
    protected function processResponse(ResponseInterface $response): array
    {
        $statusCode = $response->getStatusCode();
        if ($statusCode !== 200) {
            throw new RemoteServiceException('Status code not successfully');
        }

        if (!json_validate((string)$response->getBody())) {
            throw new InvalidJsonException('Invalid JSON response');
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

        return [
            new CurrencyProviderDto($this->importCurrency, round($conversionRate, 5))
        ];
    }
}
