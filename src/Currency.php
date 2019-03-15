<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Rixafy\DoctrineTraits\ActiveTrait;
use Rixafy\DoctrineTraits\DateTimeTrait;
use Rixafy\DoctrineTraits\UniqueTrait;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="currency")
 */
class Currency
{
    use UniqueTrait;
    use ActiveTrait;
    use DateTimeTrait;

    /**
     * @ORM\Column(type="string", unique=true, length=3)
     * @var string
     */
    private $code;

    /**
     * @ORM\Column(type="float")
     * @var float
     */
    private $rate;

    /**
     * @ORM\Column(type="string", length=3)
     * @var string
     */
    private $symbol_before;

    /**
     * @ORM\Column(type="string", length=3)
     * @var string
     */
    private $symbol_after;

    /**
     * @ORM\Column(type="smallint")
     * @var int
     */
    private $decimal_places;

    /**
     * @ORM\Column(type="string", length=1)
     * @var string
     */
    private $decimal_separator;

    /**
     * @ORM\Column(type="string", length=1)
     * @var string
     */
    private $thousands_separator;

    /**
     * @ORM\Column(type="boolean")
     * @var bool
     */
    private $round_down = false;

    /**
     * Currency constructor.
     * @param CurrencyData $currencyData
     */
    public function __construct(CurrencyData $currencyData)
    {
        $this->code = $currencyData->code;
    }

    /**
     * @param CurrencyData $currencyData
     */
    public function edit(CurrencyData $currencyData)
    {
        $this->code = $currencyData->code;
        $this->rate = $currencyData->rate;
        $this->symbol_before = $currencyData->symbolBefore;
        $this->symbol_after = $currencyData->symbolAfter;
        $this->decimal_places = $currencyData->decimalPlaces;
        $this->decimal_separator = $currencyData->decimalSeparator;
        $this->thousands_separator = $currencyData->thousandsSeparator;
        $this->round_down = $currencyData->roundDown;
    }

    /**
     * @return CurrencyData
     */
    public function getData(): CurrencyData
    {
        $data = new CurrencyData();

        $data->code = $this->code;
        $data->rate = $this->rate;
        $data->symbolBefore = $this->symbol_before;
        $data->symbolAfter = $this->symbol_after;
        $data->decimalPlaces = $this->decimal_places;
        $data->decimalSeparator = $this->decimal_separator;
        $data->thousandsSeparator = $this->thousands_separator;
        $data->roundDown = $this->round_down;

        return $data;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return float
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * @return string
     */
    public function getSymbolBefore(): string
    {
        return $this->symbol_before;
    }

    /**
     * @return string
     */
    public function getSymbolAfter(): string
    {
        return $this->symbol_after;
    }

    /**
     * @return int
     */
    public function getDecimalPlaces(): int
    {
        return $this->decimal_places;
    }

    /**
     * @return bool
     */
    public function isRoundDown(): bool
    {
        return $this->round_down;
    }

    /**
     * @return string
     */
    public function getDecimalSeparator(): string
    {
        return $this->decimal_separator;
    }

    /**
     * @param float $amount
     * @param Currency|null $fromCurrency
     * @return string
     */
    public function formatToString(float $amount, ?Currency $fromCurrency = null): string
    {
        return $this->symbol_before.$this->formatToNumber($amount, $fromCurrency).$this->symbol_after;
    }

    /**
     * @param float $amount
     * @param Currency|null $fromCurrency
     * @return string
     */
    public function formatToNumber(float $amount, ?Currency $fromCurrency = null): string
    {
        if ($fromCurrency !== null) {
            return number_format((float) $this->convert($amount, $fromCurrency), $this->decimal_places, $this->decimal_separator, $this->thousands_separator);
        }

        return number_format((float) $amount, $this->decimal_places, $this->decimal_separator, $this->thousands_separator);
    }

    /**
     * @param float $amount
     * @param Currency $fromCurrency
     * @return float
     */
    public function convert(float $amount, Currency $fromCurrency): float
    {
        return round($amount * ($this->getRate() / $fromCurrency->getRate()), $this->decimal_places, $this->round_down ? PHP_ROUND_HALF_DOWN : PHP_ROUND_HALF_UP);
    }

    /**
     * @param float $rate
     */
    public function updateRate(float $rate)
    {
        $this->rate = $rate;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getCode();
    }
}