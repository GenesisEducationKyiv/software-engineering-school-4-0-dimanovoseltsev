<?php

namespace app\currencies\infrastructure\providers;

use app\shared\application\exceptions\RemoteServiceException;
use PHPUnit\Util\InvalidJsonException;
use Psr\Http\Message\ResponseInterface;

abstract class BaseProvider
{
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
     * @return array
     */
    protected function parseJsonBody(ResponseInterface $response): array
    {
        if (!json_validate((string)$response->getBody())) {
            throw new InvalidJsonException('Invalid JSON response');
        }

        return (array)json_decode((string)$response->getBody(), true);
    }
}
