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
	 * @throws Exception\CurrencyNotFoundException
	 */
	public function setup(string $currencyCode): void
	{
		$this->currency = $this->currencyFacade->getByCode($currencyCode);
	}

	public function setupFromEntity(Currency $currency): void
	{
		$this->currency = $currency;
	}
    
    /**
     * @throws CurrencyNotProvidedException
     */
    public function provide(): Currency
    {
        if ($this->currency === null) {
            throw new CurrencyNotProvidedException('Currency was not provided (use \Rixafy\Currency\CurrencyProvider::provide(string $currencyCode)) and default currency is missing.');
        }
        return $this->currency;
    }

    /**
     * @throws Exception\CurrencyNotFoundException
     */
    public function change(string $currencyCode): void
    {
        $this->setup($currencyCode);
    }
}
