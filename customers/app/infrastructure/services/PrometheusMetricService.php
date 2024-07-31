<?php

namespace app\infrastructure\services;

use app\application\enums\CurrencyIso;
use app\application\enums\State;
use app\application\services\MetricServiceInterface;
use Prometheus\Exception\MetricsRegistrationException;
use Prometheus\RegistryInterface;
use Prometheus\RendererInterface;

class PrometheusMetricService implements MetricServiceInterface
{
    /**
     * @param RegistryInterface $registry
     * @param RendererInterface $renderer
     * @param string $namespace
     */
    public function __construct(
        private readonly RegistryInterface $registry,
        private readonly RendererInterface $renderer,
        private readonly string $namespace = 'app',
    ) {
    }

    /**
     * @throws MetricsRegistrationException
     */
    public function trackHttRequest(string $method, string $endpoint, int $statusCode): void
    {
        $counter = $this->registry->getOrRegisterCounter(
            $this->namespace,
            'http_requests',
            'Total number of HTTP requests',
            ['method', 'endpoint', 'status']
        );
        $counter->inc([$method, $endpoint, (string)$statusCode]);
    }

    /**
     * @throws MetricsRegistrationException
     */
    public function trackCommands(string $command, State $state): void
    {
        $counter = $this->registry->getOrRegisterCounter(
            $this->namespace,
            'console_commands',
            'Total number of run console commands',
            ['command', 'status']
        );
        $counter->inc([$command, $state->value]);
    }

    /**
     * @throws MetricsRegistrationException
     */
    public function trackHttpRequestDurations(string $method, string $endpoint, int $duration): void
    {
        $histogram = $this->registry->getOrRegisterHistogram(
            $this->namespace,
            'http_request_durations',
            'Duration of HTTP requests in milliseconds',
            ['method', 'endpoint'],
            [10, 50, 100, 250, 500, 1000]
        );

        $histogram->observe($duration, [$method, $endpoint]);
    }

    /**
     * @throws MetricsRegistrationException
     */
    public function trackCommandsDurations(string $command, int $duration): void
    {
        $histogram = $this->registry->getOrRegisterHistogram(
            $this->namespace,
            'console_command_durations',
            'Duration of run console commands in milliseconds',
            ['command'],
            [100, 500, 1000, 5000]
        );

        $histogram->observe($duration, [$command]);
    }

    public function export(): string
    {
        return $this->renderer->render($this->registry->getMetricFamilySamples());
    }
}
