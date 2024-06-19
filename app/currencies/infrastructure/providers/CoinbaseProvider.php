<?php

namespace app\currencies\infrastructure\providers;

use app\currencies\application\dto\CurrencyProviderDto;
use app\currencies\application\providers\ProviderInterface;
use app\shared\application\exceptions\RemoteServiceException;
use app\shared\application\exceptions\UnexpectedValueException;
use Exception;
use GuzzleHttp\Client as HttpClient;
use Psr\Http\Message\ResponseInterface;
use Throwable;
use Yii;

class CoinbaseProvider extends BaseProvider implements ProviderInterface
{
    /**
     * CoinbaseProvider constructor.
     * @param HttpClient $client
     * @param string $baseCurrency
     * @param string $importCurrency
     */
    public function __construct(
        private readonly HttpClient $client,
        private readonly string $baseCurrency = "USD",
        private readonly string $importCurrency = "USD",
    ) {
    }


    /**
     * @return CurrencyProviderDto[]
     * @throws RemoteServiceException
     * @see https://docs.cdp.coinbase.com/sign-in-with-coinbase/docs/api-prices/#get-buy-price
     */
    public function getActualRates(): array
    {
        $url = sprintf("/v2/prices/%s-%s/buy", $this->baseCurrency, $this->importCurrency);
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
        $this->checkStatusCode($response, 200);
        $body = $this->parseJsonBody($response);
        if (empty($body['data']['amount'])) {
            throw new UnexpectedValueException('Bad conversion rate');
        }

        return [
            new CurrencyProviderDto($this->importCurrency, round((float)$body['data']['amount'], 5))
        ];
    }
}
