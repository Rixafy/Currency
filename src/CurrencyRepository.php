<?php

declare(strict_types=1);

namespace Rixafy\Currency;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Ramsey\Uuid\UuidInterface;
use Rixafy\Currency\Exception\CurrencyNotFoundException;

class CurrencyRepository
{
    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    protected function getRepository()
    {
        return $this->entityManager->getRepository(Currency::class);
    }

	/**
	 * @throws CurrencyNotFoundException
	 */
	public function get(UuidInterface $id): Currency
	{
		/** @var Currency $currency */
		$currency = $this->getRepository()->findOneBy([
			'id' => $id
		]);

		if ($currency === null) {
			throw CurrencyNotFoundException::byId($id);
		}

		return $currency;
	}

    /**
     * @throws CurrencyNotFoundException
     */
    public function getByCode(string $code): Currency
    {
    	/** @var Currency $currency */
        $currency = $this->getRepository()->findOneBy([
            'code' => $code
        ]);

        if ($currency === null) {
            throw CurrencyNotFoundException::byCode($code);
        }

        return $currency;
    }

    /**
     * @throws CurrencyNotFoundException
     */
    public function getDefault(): Currency
    {
    	/** @var Currency $currency */
        $currency = $this->getRepository()->findOneBy([
            'rate' => 1
        ]);

        if ($currency === null) {
            throw CurrencyNotFoundException::byRate(1);
        }

        return $currency;
    }

    public function getQueryBuilderForAll(): QueryBuilder
    {
        return $this->getRepository()->createQueryBuilder('c');
    }

    public function getQueryBuilderForAllActive(): QueryBuilder
    {
        return $this->getRepository()->createQueryBuilder('c')
            ->where('c.is_active = true');
    }
}
