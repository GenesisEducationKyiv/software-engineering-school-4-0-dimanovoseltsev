<?php

namespace app\infrastructure\providers;

use app\application\exceptions\RemoteServiceException;
use app\application\services\LogServiceInterface;
use PHPUnit\Util\InvalidJsonException;
use Psr\Http\Message\ResponseInterface;

abstract class BaseProvider
{
    protected const string PROVIDER = "";

    /**
     * @param LogServiceInterface $logService
     */
    public function __construct(protected LogServiceInterface $logService)
    {
    }

    /**
     * @throws RemoteServiceException
     */
    protected function checkStatusCode(ResponseInterface $response, int $expectedCode): void
    {
        $statusCode = $response->getStatusCode();
        if ($statusCode !== $expectedCode) {
            throw new RemoteServiceException('Status code not successfully');
        }
    }

    /**
     * @param ResponseInterface $response
     * @return array<string,mixed>
     */
    protected function parseJsonBody(ResponseInterface $response): array
    {
        $body = (string)$response->getBody()->getContents();
        $this->logService->log(sprintf("%s - Response: %s", static::PROVIDER, $body));

        if (!json_validate($body)) {
            throw new InvalidJsonException('Invalid JSON response');
        }

        return (array)json_decode($body, true);
    }
}
