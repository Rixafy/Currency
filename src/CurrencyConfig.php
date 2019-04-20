<?php

declare(strict_types=1);

namespace Rixafy\Currency;

class CurrencyConfig
{
    /**
     * Api key from third-party api.
     *
     * @var string
     */
    private $apiKey;

    /**
     * Api service for fetching currencies.
     *
     * @var string
     */
    private $apiService;

    /**
     * Base currency, rate for this currency will be always 1, rates for other currencies will be relative to this.
     *
     * @var string by default, USD
     */
    private $baseCurrency;

    public function __construct(string $baseCurrency, ?string $apiService, ?string $apiKey)
    {
        $this->apiKey = $apiKey;
        $this->apiService = $apiService;
        $this->baseCurrency = $baseCurrency;
    }

    public function getApiKey(): ?string
    {
        return $this->apiKey;
    }

    public function getBaseCurrency(): string
    {
        return $this->baseCurrency;
    }

    public function getApiService(): ?string
    {
        return $this->apiService;
    }

    public function setApiKey(string $apiKey): void
    {
        $this->apiKey = $apiKey;
    }

    public function setApiService(string $apiService): void
    {
        $this->apiService = $apiService;
    }

    public function setBaseCurrency(string $baseCurrency): void
    {
        $this->baseCurrency = $baseCurrency;
    }
}
