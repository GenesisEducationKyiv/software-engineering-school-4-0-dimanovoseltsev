<?php

namespace app\application\services;

use app\application\enums\CurrencyIso;
use app\application\enums\State;

interface MetricServiceInterface
{
    public function trackHttRequest(string $method, string $endpoint, int $statusCode): void;

    public function trackHttpRequestDurations(string $method, string $endpoint, int $duration): void;

    public function trackSendEmailTotal(CurrencyIso $currency): void;

    public function export(): string;

    public function trackCommands(string $command, State $state): void;

    public function trackCommandsDurations(string $command, int $duration): void;
}
