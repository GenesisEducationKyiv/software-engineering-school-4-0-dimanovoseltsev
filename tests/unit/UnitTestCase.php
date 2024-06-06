<?php

namespace tests\unit;


use app\links\application\services\LinkService;
use app\links\domain\entities\Link;
use app\links\infrastructure\mappers\Mapper;
use app\links\infrastructure\repositories\LinkRepository;
use app\models\Currency;
use app\models\Subscription;
use app\shared\infrastructure\adapters\Queue;
use app\shared\infrastructure\adapters\Redis;
use app\statistics\application\services\StatisticService;
use app\statistics\domain\entities\Statistic;
use app\tokenGenerator\application\services\TokenGeneratorService;
use app\tokenGenerator\domain\entities\Token;
use app\urls\application\services\UrlService;
use app\urls\domain\entities\Url;
use app\users\application\services\UserService;
use app\users\domain\entities\User;
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
     * @return Subscription|MockObject
     */
    protected function getSubscriptionModelMock(array $data = []): Subscription|MockObject
    {
        $mock = $this->getMockBuilder(Subscription::class)
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
            ->willReturn((new Subscription())->attributes());

        $mock = $this->setAttributes($mock, $data);

        return $mock;
    }

    /**
     * @param array $data
     * @return Currency|MockObject
     */
    protected function getCurrencyModelMock(array $data = []): Currency|MockObject
    {
        $mock = $this->getMockBuilder(Currency::class)
            ->disableOriginalConstructor()
            ->onlyMethods([
                'attributes',
                'load',
                'save',
            ])
            ->getMock();

        $mock->expects(self::any())
            ->method('attributes')
            ->willReturn((new Currency())->attributes());

        $mock = $this->setAttributes($mock, $data);

        return $mock;
    }
}
