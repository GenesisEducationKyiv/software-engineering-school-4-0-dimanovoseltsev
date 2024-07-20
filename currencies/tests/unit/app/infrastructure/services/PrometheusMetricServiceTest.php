<?php

namespace tests\unit\app\infrastructure\services;

use app\application\enums\CurrencyIso;
use app\application\enums\State;
use app\infrastructure\services\PrometheusMetricService;
use PHPUnit\Framework\MockObject\MockObject;
use Prometheus\CollectorRegistry;
use Prometheus\Counter;
use Prometheus\Exception\MetricsRegistrationException;
use Prometheus\RenderTextFormat;
use tests\unit\UnitTestCase;

class PrometheusMetricServiceTest extends UnitTestCase
{
    private PrometheusMetricService $service;
    private CollectorRegistry|MockObject $registry;
    private RenderTextFormat|MockObject $render;
    private string $namespace = 'app1';

    public function setUp(): void
    {
        parent::setUp();
        $this->registry = $this->getCollectorRegistryMock();
        $this->render = $this->getRenderMock();

        $this->service = new PrometheusMetricService($this->registry, $this->render, $this->namespace);
    }

    /**
     * @return RenderTextFormat|MockObject
     */
    protected function getRenderMock(): RenderTextFormat|MockObject
    {
        return $this->getMockBuilder(RenderTextFormat::class)
            ->disableOriginalConstructor()
            ->onlyMethods(
                ['render']
            )
            ->getMock();
    }

    /**
     * @return CollectorRegistry|MockObject
     */
    protected function getCollectorRegistryMock(): CollectorRegistry|MockObject
    {
        return $this->getMockBuilder(CollectorRegistry::class)
            ->disableOriginalConstructor()
            ->onlyMethods(
                ['getOrRegisterCounter', 'getOrRegisterHistogram', 'getMetricFamilySamples']
            )
            ->getMock();
    }

    /**
     * @return Counter|MockObject
     */
    protected function getCounterMock(): Counter|MockObject
    {
        return $this->getMockBuilder(Counter::class)
            ->disableOriginalConstructor()
            ->onlyMethods(
                ['inc']
            )
            ->getMock();
    }

    public function testTrackHttRequest()
    {
        $method = 'GET';
        $endpoint = '/a1';
        $statusCode = 200;

        $counter = $this->getCounterMock();
        $counter->expects($this->once())
            ->method('inc')
            ->with([$method, $endpoint, $statusCode]);

        $this->registry->expects($this->once())
            ->method('getOrRegisterCounter')
            ->with(
                $this->namespace,
                'http_requests',
                'Total number of HTTP requests',
                ['method', 'endpoint', 'status']
            )
            ->willReturn($counter);

        $this->service->trackHttRequest($method, $endpoint, $statusCode);
    }

    /**
     * @throws MetricsRegistrationException
     */
    public function testTrackCommands()
    {
        $command = 'app/a1';
        $state = State::from('successful');

        $counter = $this->getCounterMock();
        $counter->expects($this->once())
            ->method('inc')
            ->with([$command, $state->value]);

        $this->registry->expects($this->once())
            ->method('getOrRegisterCounter')
            ->with(
                $this->namespace,
                'console_commands',
                'Total number of run console commands',
                ['command', 'status']
            )
            ->willReturn($counter);

        $this->service->trackCommands($command, $state);
    }

    /**
     * @throws MetricsRegistrationException
     */
    public function testTrackCurrencyImportsTotal()
    {
        $imported = 10;

        $counter = $this->getCounterMock();
        $counter->expects($this->once())
            ->method('inc')
            ->with([$imported]);

        $this->registry->expects($this->once())
            ->method('getOrRegisterCounter')
            ->with(
                $this->namespace,
                'currency_import_totals',
                'Total number of currencies imported',
                ['imported']
            )
            ->willReturn($counter);

        $this->service->trackCurrencyImportsTotal($imported);
    }


    /**
     * @throws MetricsRegistrationException
     */
    public function testTrackCurrencyRateRequestTotal()
    {
        $currency = CurrencyIso::from('USD');
        $state = State::from('successful');

        $counter = $this->getCounterMock();
        $counter->expects($this->once())
            ->method('inc')
            ->with([$currency->value, $state->value]);

        $this->registry->expects($this->once())
            ->method('getOrRegisterCounter')
            ->with(
                $this->namespace,
                'currency_rate_requests',
                'Total number of currency rate requests',
                ['currency', 'result']
            )
            ->willReturn($counter);

        $this->service->trackCurrencyRateRequestTotal($currency, $state);
    }
}
