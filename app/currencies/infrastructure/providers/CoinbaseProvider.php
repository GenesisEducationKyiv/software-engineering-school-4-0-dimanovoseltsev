<?php

namespace app\currencies\infrastructure\providers;

use app\currencies\application\dto\CurrencyProviderDto;
use app\currencies\application\providers\ProviderInterface;
use app\shared\application\exceptions\RemoteServiceException;
use app\shared\application\exceptions\UnexpectedValueException;
use GuzzleHttp\Client as HttpClient;
use Psr\Http\Message\ResponseInterface;
use Throwable;
use Yii;

class CoinbaseProvider extends BaseProvider implements ProviderInterface
{
    /**
     * CoinbaseProvider constructor.
     * @param HttpClient $client
     */
    public function __construct(
        private readonly HttpClient $client,
    ) {
    }


    /**
     * @param string $sourceCurrency
     * @param string $targetCurrency
     * @return CurrencyProviderDto
     * @throws RemoteServiceException
     * @see https://docs.cdp.coinbase.com/sign-in-with-coinbase/docs/api-prices/#get-buy-price
     */
    public function getRate(string $sourceCurrency, string $targetCurrency): CurrencyProviderDto
    {
        $url = sprintf("/v2/prices/%s-%s/buy", $sourceCurrency, $targetCurrency);
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

        /** @var array{data?: array<array{amount: float}>} $body */
        $body = $this->parseJsonBody($response);
        if (empty($body['data']['amount'])) {
            throw new UnexpectedValueException('Bad conversion rate');
        }

        return new CurrencyProviderDto($targetCurrency, round((float)$body['data']['amount'], 5));
    }
}
