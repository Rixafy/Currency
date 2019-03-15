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
    private $symbol_before = '';

    /**
     * @ORM\Column(type="string", length=3)
     * @var string
     */
    private $symbol_after = '';

    /**
     * @ORM\Column(type="smallint")
     * @var int
     */
    private $decimal_places = 2;

    /**
     * @ORM\Column(type="string", length=1)
     * @var string
     */
    private $decimal_separator = '.';

    /**
     * @ORM\Column(type="string", length=1)
     * @var string
     */
    private $thousands_separator = ' ';

    /**
     * @ORM\Column(type="boolean")
     * @var bool
     */
    private $round_down = false;

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
     * @param float $rate
     */
    public function setRate($rate)
    {
        $this->rate = $rate;
    }

    /**
     * @return string
     */
    public function getSymbolBefore(): string
    {
        return $this->symbol_before;
    }

    /**
     * @param string $symbol_before
     */
    public function setSymbolBefore(string $symbol_before): void
    {
        $this->symbol_before = $symbol_before;
    }

    /**
     * @return string
     */
    public function getSymbolAfter(): string
    {
        return $this->symbol_after;
    }

    /**
     * @param string $symbol_after
     */
    public function setSymbolAfter(string $symbol_after): void
    {
        $this->symbol_after = $symbol_after;
    }

    /**
     * @return int
     */
    public function getDecimalPlaces(): int
    {
        return $this->decimal_places;
    }

    /**
     * @param int $decimal_places
     */
    public function setDecimalPlaces(int $decimal_places): void
    {
        $this->decimal_places = $decimal_places;
    }

    /**
     * @return bool
     */
    public function isRoundDown(): bool
    {
        return $this->round_down;
    }

    /**
     * @param bool $round_down
     */
    public function setRoundDown(bool $round_down): void
    {
        $this->round_down = $round_down;
    }

    /**
     * @return string
     */
    public function getDecimalSeparator(): string
    {
        return $this->decimal_separator;
    }

    /**
     * @param string $decimal_separator
     */
    public function setDecimalSeparator(string $decimal_separator): void
    {
        $this->decimal_separator = $decimal_separator;
    }

    public function formatToString(float $amount, ?Currency $fromCurrency = null): string
    {
        return $this->symbol_before.$this->formatToNumber($amount, $fromCurrency).$this->symbol_after;
    }

    public function formatToNumber(float $amount, ?Currency $fromCurrency = null): string
    {
        if ($fromCurrency !== null) {
            return number_format((float) $this->convert($amount, $fromCurrency), $this->decimal_places, $this->decimal_separator, $this->thousands_separator);
        }

        return number_format((float) $amount, $this->decimal_places, $this->decimal_separator, $this->thousands_separator);
    }

    public function convert(float $amount, Currency $fromCurrency): float
    {
        return round($amount * ($this->getRate() / $fromCurrency->getRate()), $this->decimal_places, $this->round_down ? PHP_ROUND_HALF_DOWN : PHP_ROUND_HALF_UP);
    }

    public function __toString()
    {
        return $this->getCode();
    }
}