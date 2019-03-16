<?php

declare(strict_types=1);

namespace Rixafy\Currency;

class CurrencyFactory
{
    public function create(CurrencyData $imageData): Currency
    {
        return new Currency($imageData);
    }
}