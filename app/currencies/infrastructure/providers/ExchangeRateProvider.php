<?php

namespace app\currencies\infrastructure\providers;

use app\currencies\application\dto\CurrencyProviderDto;
use app\currencies\application\providers\ProviderInterface;
use app\shared\application\exceptions\RemoteServiceException;
use app\shared\application\exceptions\UnexpectedValueException;
use app\shared\application\services\LogServiceInterface;
use GuzzleHttp\Client as HttpClient;
use Psr\Http\Message\ResponseInterface;
use Throwable;
use Yii;

class ExchangeRateProvider extends BaseProvider implements ProviderInterface
{
    final protected const string PROVIDER = "exchangerate-api.com";

    /**
     * ExchangeRateProvider constructor.
     * @param LogServiceInterface $logService
     * @param HttpClient $client
     * @param string $apiKey
     */
    public function __construct(
        private readonly HttpClient $client,
        private readonly string $apiKey,
        LogServiceInterface $logService,
    ) {
        parent::__construct($logService);
    }

    /**
     * @param string $sourceCurrency
     * @param string $targetCurrency
     * @return CurrencyProviderDto
     * @throws RemoteServiceException
     * @see https://www.exchangerate-api.com/docs/pair-conversion-requests
     */
    public function getRate(string $sourceCurrency, string $targetCurrency): CurrencyProviderDto
    {
        $url = sprintf("/v6/%s/pair/%s/%s", $this->apiKey, $sourceCurrency, $targetCurrency);
        try {
            $response = $this->client->get($url);
            return $this->processResponse($response, $targetCurrency);
        } catch (RemoteServiceException $e) {
            throw $e;
        } catch (Throwable $e) {
            Yii::error($e);
            throw new RemoteServiceException("Service unavailable: {$e->getMessage()}", previous: $e);
        }
    }

    /**
     * @param ResponseInterface $response
     * @param string $targetCurrency
     * @return CurrencyProviderDto
     * @throws RemoteServiceException
     * @throws UnexpectedValueException
     */
    protected function processResponse(ResponseInterface $response, string $targetCurrency): CurrencyProviderDto
    {
        $this->checkStatusCode($response, 200);
        /** @var array{result?: string, conversion_rate?: float} $body */
        $body = $this->parseJsonBody($response);

        $result = $body['result'] ?? null;
        if ($result !== "success") {
            throw new UnexpectedValueException('Result not success');
        }

        $conversionRate = (float)($body['conversion_rate'] ?? 0);
        if (empty($conversionRate)) {
            throw new UnexpectedValueException('Bad conversion rate');
        }

        return new CurrencyProviderDto($targetCurrency, round($conversionRate, 5));
    }
}
