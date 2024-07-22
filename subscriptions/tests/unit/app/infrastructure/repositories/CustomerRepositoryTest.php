<?php

namespace tests\unit\app\infrastructure\repositories;

use app\application\exceptions\RemoteServiceException;
use app\domain\entities\Customer;
use app\infrastructure\repositories\CustomerRepository;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\MockObject\MockObject;
use tests\unit\UnitTestCase;
use yii\db\Exception;

class CustomerRepositoryTest extends UnitTestCase
{
    private CustomerRepository $repository;
    private Client|MockObject $httClient;

    public function setUp(): void
    {
        parent::setUp();
        $this->httClient = $this->getHttpClientMock();
        $this->repository = new CustomerRepository($this->httClient);
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
                    'post',
                    'delete',
                ]
            )
            ->getMock();
    }


    /**
     * @throws Exception|RemoteServiceException
     */
    public function testSave()
    {
        $email = "mai@mail.com";
        $customerData = $this->getCustomerEntityDefault();

        $this->httClient->expects(self::once())
            ->method('post')
            ->with('/customer', [
                'json' => ['email' => $email]
            ])
            ->willReturn(new Response(200, [], json_encode($customerData)));

        $actual = $this->repository->create($email);
        self::assertInstanceOf(Customer::class, $actual);
    }

    public function testSaveNot200()
    {
        self::expectException(RemoteServiceException::class);
        self::expectExceptionMessage("Return 500 status code. Response:");

        $email = "mai@mail.com";
        $this->httClient->expects(self::once())
            ->method('post')
            ->with('/customer', [
                'json' => ['email' => $email]
            ])
            ->willReturn(new Response(500, [], json_encode(['error' => 'error'])));

        $actual = $this->repository->create($email);
    }

    public function testSaveNotValidJson()
    {
        self::expectException(RemoteServiceException::class);
        self::expectExceptionMessage("Invalid json");

        $email = "mai@mail.com";

        $this->httClient->expects(self::once())
            ->method('post')
            ->with('/customer', [
                'json' => ['email' => $email]
            ])
            ->willReturn(new Response(200, [], 'error'));

        $actual = $this->repository->create($email);
    }

    /**
     * @throws Exception|RemoteServiceException
     */
    public function testDelete()
    {
        $email = "mai@mail.com";

        $this->httClient->expects(self::once())
            ->method('delete')
            ->with('/customer', [
                'json' => ['email' => $email]
            ])
            ->willReturn(new Response(204, [], null));

        $actual = $this->repository->delete($email);
        self::assertEquals(1, $actual);
    }

    /**
     * @throws Exception|RemoteServiceException
     */
    public function testDeleteNot204()
    {
        self::expectException(RemoteServiceException::class);
        self::expectExceptionMessage("Return 500 status code. Response:");

        $email = "mai@mail.com";

        $this->httClient->expects(self::once())
            ->method('delete')
            ->with('/customer', [
                'json' => ['email' => $email]
            ])
            ->willReturn(new Response(500, [], json_encode(['error' => 'error'])));

        $actual = $this->repository->delete($email);
    }

    /**
     * @throws Exception|RemoteServiceException
     */
    public function testDeleteError()
    {
        self::expectException(RemoteServiceException::class);
        self::expectExceptionMessage("Error");

        $email = "mai@mail.com";

        $this->httClient->expects(self::once())
            ->method('delete')
            ->willThrowException(new \Exception('Error'));

        $actual = $this->repository->delete($email);
    }
}

