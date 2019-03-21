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

    /**
     * CurrencyConfig constructor.
     * @param $apiKey
     * @param string $apiService
     * @param string $baseCurrency
     */
    public function __construct($apiKey = 'undefined', $apiService = 'fixer', $baseCurrency = 'USD')
    {
        $this->apiKey = $apiKey;
        $this->apiService = $apiService;
        $this->baseCurrency = $baseCurrency;
    }

    /**
     * @return string
     */
    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    /**
     * @return string
     */
    public function getBaseCurrency(): string
    {
        return $this->baseCurrency;
    }

    /**
     * @return string
     */
    public function getApiService(): string
    {
        return $this->apiService;
    }

    /**
     * @param string $apiKey
     */
    public function setApiKey(string $apiKey): void
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @param string $apiService
     */
    public function setApiService(string $apiService): void
    {
        $this->apiService = $apiService;
    }

    /**
     * @param string $baseCurrency
     */
    public function setBaseCurrency(string $baseCurrency): void
    {
        $this->baseCurrency = $baseCurrency;
    }
}