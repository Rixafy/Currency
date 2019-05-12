<?php

declare(strict_types=1);

namespace Rixafy\Currency;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
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

	/**
	 * @return EntityRepository|ObjectRepository
	 */
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
        return $this->getRepository()->createQueryBuilder('e');
    }

    public function getQueryBuilderForAllActive(): QueryBuilder
    {
        return $this->getQueryBuilderForAll()
            ->where('e.is_active = true');
    }
}
