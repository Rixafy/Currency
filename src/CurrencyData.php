<?php

declare(strict_types=1);

namespace Rixafy\Currency;

class CurrencyData
{
    /** @var string */
    public $code;

    /** @var float */
    public $rate;

    /** @var string */
    public $symbolBefore = '';

    /** @var string */
    public $symbolAfter = '';

    /** @var int */
    public $decimalPlaces = 2;

    /** @var string */
    public $decimalSeparator = '.';

    /** @var string */
    public $thousandsSeparator = ' ';

    /** @var bool */
    public $roundDown = false;
}
