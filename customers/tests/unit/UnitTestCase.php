<?php

namespace tests\unit;

use app\application\services\CustomerService;
use app\domain\entities\Customer;
use app\infrastructure\mappers\CustomerMapper;
use app\infrastructure\models\Customer as ModelCustomer;
use app\infrastructure\services\YiiLogger;
use Closure;
use Codeception\PHPUnit\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\Rule\InvocationOrder;
use ReflectionClass;
use ReflectionException;

class UnitTestCase extends TestCase
{
    /**
     * @param InvocationOrder $matcher
     * @param array|null $params
     * @param array|null $returnValues
     * @param null $returnDefault
     * @return Closure
     */
    protected function willReturnCallbackPrepare(
        InvocationOrder $matcher,
        ?array $params = null,
        ?array $returnValues = null,
        $returnDefault = null
    ) {
        return function () use ($matcher, $params, $returnValues, $returnDefault) {
            $params = is_array($params) ? array_values($params) : $params;
            $returnValues = is_array($returnValues) ? array_values($returnValues) : $returnValues;
            $index = $matcher->numberOfInvocations() - 1;

            if (is_array($params)) {
                $arguments = func_get_args();
                self::assertArrayHasKey($index, $params, 'params not contains ' . $index);
                $this->assertEquals($params[$index], $arguments);
            }

            if (is_array($returnValues)) {
                self::assertArrayHasKey($index, $returnValues, 'return not contains ' . $index);
            }
            return $returnValues[$index] ?? $returnDefault;
        };
    }

    /**
     * Call protected/private method of a class.
     *
     * @param object &$object Instantiated object that we will run method on.
     * @param string $methodName Method name to call
     * @param array $parameters Array of parameters to pass into method.
     *
     * @return mixed Method return.
     * @throws ReflectionException
     */
    protected function invokeMethod(object &$object, string $methodName, array $parameters = [])
    {
        $reflection = new ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

    /**
     * @param object $object
     * @param array $data
     * @return object
     */
    public function setAttributes(object $object, array $data = []): object
    {
        foreach ($data as $k => $v) {
            $object->$k = $v;
        }
        return $object;
    }


    /**
     * @return array
     */
    protected function getCustomerEntityDefault(): array
    {
        return [
            'id' => 1,
            'email' => 'mail@mail.com',
        ];
    }

    /**
     * @param array $data
     * @return Customer
     */
    protected function getCustomerEntity(array $data = []): Customer
    {
        $data = array_merge($this->getCustomerEntityDefault(), $data);

        return CustomerMapper::fromPrimitive($data);
    }


    /**
     * @param string $class
     * @return MockObject
     */
    protected function getActionMock(string $class): MockObject
    {
        return $this->getMockBuilder($class)
            ->disableOriginalConstructor()
            ->onlyMethods(['execute'])
            ->getMock();
    }

    /**
     * @return YiiLogger|MockObject
     */
    protected function getLogServiceMock(): YiiLogger|MockObject
    {
        return $this->getMockBuilder(YiiLogger::class)
            ->disableOriginalConstructor()
            ->onlyMethods([
                'error',
                'warn',
                'info',
                'debug',
            ])
            ->getMock();
    }

    /**
     * @param array $data
     * @return ModelCustomer|MockObject
     */
    protected function getCustomerModelMock(array $data = []): ModelCustomer|MockObject
    {
        $mock = $this->getMockBuilder(ModelCustomer::class)
            ->disableOriginalConstructor()
            ->onlyMethods([
                'attributes',
                'load',
                'save',
            ])
            ->getMock();

        $mock->expects(self::any())
            ->method('attributes')
            ->willReturn((new ModelCustomer())->attributes());

        return $this->setAttributes($mock, $data);
    }

    /**
     * @return CustomerService|MockObject
     */
    protected function getCustomerServiceMock(): CustomerService|MockObject
    {
        return $this->getMockBuilder(CustomerService::class)
            ->disableOriginalConstructor()
            ->onlyMethods(
                [
                    'getByEmail',
                    'save',
                    'delete',
                ]
            )
            ->getMock();
    }
}
