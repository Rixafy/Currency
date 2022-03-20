<?php

declare(strict_types=1);

namespace Rixafy\Currency;

use Doctrine\ORM\Mapping as ORM;
use Rixafy\DoctrineTraits\ActiveTrait;
use Rixafy\DoctrineTraits\DateTimeTrait;
use Rixafy\DoctrineTraits\UniqueTrait;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: 'currency')]
#[ORM\Index(columns: ['rate'], name: 'search_default')]
class Currency
{
    use UniqueTrait;
    use ActiveTrait;
    use DateTimeTrait;

	#[ORM\Column(length: 3, unique: true)]
    private string $code;

	#[ORM\Column]
    private float $rate;

	#[ORM\Column(length: 3)]
    private string $symbolBefore;

	#[ORM\Column(length: 3)]
    private string $symbolAfter;

	#[ORM\Column(type: 'smallint')]
    private int $decimalPlaces;

	#[ORM\Column(length: 1)]
    private string $decimalSeparator;

	#[ORM\Column(length: 1)]
    private string $thousandsSeparator;

	#[ORM\Column]
    private bool $roundDown = false;

    public function __construct(CurrencyData $currencyData)
    {
        $this->code = $currencyData->code;
        $this->edit($currencyData);
    }

    public function edit(CurrencyData $currencyData): void
    {
        $this->code = $currencyData->code;
        $this->rate = $currencyData->rate;
        $this->symbolBefore = $currencyData->symbolBefore;
        $this->symbolAfter = $currencyData->symbolAfter;
        $this->decimalPlaces = $currencyData->decimalPlaces;
        $this->decimalSeparator = $currencyData->decimalSeparator;
        $this->thousandsSeparator = $currencyData->thousandsSeparator;
        $this->roundDown = $currencyData->roundDown;
    }

    public function getData(): CurrencyData
    {
        $data = new CurrencyData();
        $data->code = $this->code;
        $data->rate = $this->rate;
        $data->symbolBefore = $this->symbolBefore;
        $data->symbolAfter = $this->symbolAfter;
        $data->decimalPlaces = $this->decimalPlaces;
        $data->decimalSeparator = $this->decimalSeparator;
        $data->thousandsSeparator = $this->thousandsSeparator;
        $data->roundDown = $this->roundDown;

        return $data;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getRate(): float
    {
        return $this->rate;
    }

    public function getSymbolBefore(): string
    {
        return $this->symbolBefore;
    }

    public function getSymbolAfter(): string
    {
        return $this->symbolAfter;
    }

    public function getDecimalPlaces(): int
    {
        return $this->decimalPlaces;
    }

    public function isRoundDown(): bool
    {
        return $this->roundDown;
    }

    public function getDecimalSeparator(): string
    {
        return $this->decimalSeparator;
    }
    
    public function getThousandsSeparator(): string
    {
        return $this->thousandsSeparator;
    }

    public function formatToString(float $amount): string
    {
        return $this->symbolBefore . $this->formatToNumber($amount) . $this->symbolAfter;
    }

    public function formatToNumber(float $amount): string
    {
        return number_format((float) $amount, $this->decimalPlaces, $this->decimalSeparator, $this->thousandsSeparator);
    }

    public function convertFrom(Currency $fromCurrency, float $amount): float
    {
        return round($amount * ($this->getRate() / $fromCurrency->getRate()), $this->decimalPlaces, $this->roundDown ? PHP_ROUND_HALF_DOWN : PHP_ROUND_HALF_UP);
    }

    public function convertTo(Currency $toCurrency, float $amount): float
    {
        return $toCurrency->convertFrom($this, $amount);
    }

    public function updateRate(float $rate): void
    {
        $this->rate = $rate;
    }

    public function __toString(): string
    {
        return $this->getCode();
    }
}
