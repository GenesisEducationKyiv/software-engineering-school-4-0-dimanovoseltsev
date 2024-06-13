<?php

namespace tests\unit;

use app\currencies\application\enums\CurrencyIso;
use app\currencies\application\services\CurrencyService;
use app\currencies\domain\entities\Currency;
use app\currencies\infrastructure\mappers\Mapper;
use app\currencies\infrastructure\models\Currency as ModelCurrency;
use app\subscriptions\domain\entities\Subscription;
use app\subscriptions\infrastructure\models\Subscription as ModelSubscription;
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
     * @param array $data
     * @return ModelSubscription|MockObject
     */
    protected function getSubscriptionModelMock(array $data = []): ModelSubscription|MockObject
    {
        $mock = $this->getMockBuilder(ModelSubscription::class)
            ->disableOriginalConstructor()
            ->onlyMethods([
                'attributes',
                'load',
                'save',
                'changeLastSendAt',
            ])
            ->getMock();

        $mock->expects(self::any())
            ->method('attributes')
            ->willReturn((new ModelSubscription())->attributes());

        return $this->setAttributes($mock, $data);
    }

    /**
     * @param array $data
     * @return ModelCurrency|MockObject
     */
    protected function getCurrencyModelMock(array $data = []): ModelCurrency|MockObject
    {
        $mock = $this->getMockBuilder(ModelCurrency::class)
            ->disableOriginalConstructor()
            ->onlyMethods([
                'attributes',
                'load',
                'save',
            ])
            ->getMock();

        $mock->expects(self::any())
            ->method('attributes')
            ->willReturn((new ModelCurrency())->attributes());

        return $this->setAttributes($mock, $data);
    }


    /**
     * @return array
     */
    protected function getCurrencyEntityDefault(): array
    {
        return [
            'id' => 1,
            'iso3' => CurrencyIso::UAH->value,
            'rate' => 5.02,
            'createdAt' => strtotime('2024-01-01 00:00:00'),
            'updatedAt' => strtotime('2024-05-01 00:00:00'),
        ];
    }

    /**
     * @param array $data
     * @return Currency
     */
    protected function getCurrencyEntity(array $data = []): Currency
    {
        $data = array_merge($this->getCurrencyEntityDefault(), $data);
        return Mapper::fromPrimitive($data);
    }


    /**
     * @return array
     */
    protected function getSubscriptionEntityDefault(): array
    {
        return [
            'id' => 1,
            'email' => 'mail@mail.com',
            'createdAt' => strtotime('2024-01-01 00:00:00'),
            'updatedAt' => strtotime('2024-05-01 00:00:00'),
            'lastSendAt' => strtotime('2024-05-02 00:00:00'),
        ];
    }

    /**
     * @param array $data
     * @return Subscription
     */
    protected function getSubscriptionEntity(array $data = []): Subscription
    {
        $data = array_merge($this->getSubscriptionEntityDefault(), $data);
        return \app\subscriptions\infrastructure\mappers\Mapper::fromPrimitive($data);
    }


    /**
     * @return CurrencyService|MockObject
     */
    protected function getCurrencyServiceMock(): CurrencyService|MockObject
    {
        return $this->getMockBuilder(CurrencyService::class)
            ->disableOriginalConstructor()
            ->onlyMethods(
                [
                    'getByCode',
                    'save',
                    'create',
                ]
            )
            ->getMock();
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
}
