<?php

namespace app\infrastructure\repositories;

use app\application\exceptions\InvalidJsonException;
use app\application\exceptions\RemoteServiceException;
use app\domain\entities\Customer;
use app\domain\repositories\CustomerRepositoryInterface;
use app\infrastructure\mappers\CustomerMapper;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class CustomerRepository implements CustomerRepositoryInterface
{
    /**
     * @param Client $httpClient
     */
    public function __construct(
        private readonly Client $httpClient,
    ) {
    }

    /**
     * @param ResponseInterface $response
     * @param int $expected
     * @return void
     * @throws RemoteServiceException
     */
    private function checkStatusCode(ResponseInterface $response, int $expected): void
    {
        $statusCode = $response->getStatusCode();
        if ($statusCode !== $expected) {
            $json = (string)$response->getBody()->getContents();
            throw new RemoteServiceException(sprintf("Return %d status code. Response: %s", $statusCode, $json));
        }
    }

    /**
     * @param string $email
     * @return Customer
     * @throws RemoteServiceException
     */
    public function create(string $email): Customer
    {
        try {
            $response = $this->httpClient->post('/customer', [
                'json' => ['email' => $email]
            ]);
            $this->checkStatusCode($response, 200);
            $json = (string)$response->getBody()->getContents();

            if (!json_validate($json) || empty($json)) {
                throw new InvalidJsonException();
            }

            /** @var array{id: int, email: string} $body */
            $body = (array)json_decode($json, true);

            return CustomerMapper::fromPrimitive($body);
        } catch (RemoteServiceException $e) {
            throw $e;
        } catch (Throwable $e) {
            throw new RemoteServiceException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @param string $email
     * @return int
     * @throws RemoteServiceException
     */
    public function delete(string $email): int
    {
        try {
            $response = $this->httpClient->delete('/customer', [
                'json' => ['email' => $email]
            ]);
            $this->checkStatusCode($response, 204);
            return 1;
        } catch (RemoteServiceException $e) {
            throw $e;
        } catch (Throwable $e) {
            throw new RemoteServiceException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
