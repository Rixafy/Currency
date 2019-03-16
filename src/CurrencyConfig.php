<?php

declare(strict_types=1);

namespace Rixafy\Currency;

class CurrencyConfig
{
    /**
     * Api key from fixer.io.
     *
     * @var string
     */
    private $fixerIoApiKey;

    /**
     * Base currency, rate for this currency will be always 1, rates for other currencies will be relative to this.
     *
     * @var string by default, USD
     */
    private $baseCurrency;

    /**
     * CurrencyConfig constructor.
     * @param $fixerIoApiKey
     * @param string $baseCurrency
     */
    public function __construct($fixerIoApiKey, $baseCurrency = 'USD')
    {
        $this->fixerIoApiKey = $fixerIoApiKey;
        $this->baseCurrency = $baseCurrency;
    }

    /**
     * @return string
     */
    public function getFixerIoApiKey(): string
    {
        return $this->fixerIoApiKey;
    }

    /**
     * @return string
     */
    public function getBaseCurrency(): string
    {
        return $this->baseCurrency;
    }
}