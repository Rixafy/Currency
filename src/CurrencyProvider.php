<?php

declare(strict_types=1);

namespace Rixafy\Currency;

use Rixafy\Currency\Exception\CurrencyNotProvidedException;

class CurrencyProvider
{
    /** @var Currency */
    private $currency;

    /** @var CurrencyFacade */
    private $currencyFacade;

    public function __construct(CurrencyFacade $currencyFacade)
    {
        $this->currencyFacade = $currencyFacade;
        try {
            $this->currency = $currencyFacade->getDefault();
        } catch (Exception\CurrencyNotFoundException $e) {
        }
    }

    /**
     * @return Currency
     * @throws CurrencyNotProvidedException
     */
    public function getCurrency(): Currency
    {
        if ($this->currency === null) {
            throw new CurrencyNotProvidedException('Currency was not provided (use \Rixafy\Currency\CurrencyProvider::provide(string $currencyCode)) and default currency is missing.');
        }
        return $this->currency;
    }

    /**
     * @param string $currencyCode
     * @throws Exception\CurrencyNotFoundException
     */
    public function provide(string $currencyCode): void
    {
        $this->currency = $this->currencyFacade->getByCode($currencyCode);
    }

    /**
     * @param string $currencyCode
     * @throws Exception\CurrencyNotFoundException
     */
    public function change(string $currencyCode): void
    {
        $this->provide($currencyCode);
    }
}