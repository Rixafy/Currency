<?php

declare(strict_types=1);

namespace Rixafy\Currency\Exception;

use Exception;
use Ramsey\Uuid\UuidInterface;

class CurrencyNotFoundException extends Exception
{
	public static function byId(UuidInterface $id): self
	{
		return new self('Currency with id "' . $id . '" not found.');
	}

	public static function byCode(string $code): self
	{
		return new self('Currency with code "' . $code . '" not found.');
	}

	public static function byRate(float $rate): self
	{
		return new self('Currency with rate "' . $rate . '" not found.');
	}
}