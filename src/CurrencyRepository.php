<?php

declare(strict_types=1);

namespace Rixafy\Currency;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Ramsey\Uuid\Uuid;
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
     * @return EntityRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    protected function getRepository()
    {
        return $this->entityManager->getRepository(Currency::class);
    }

    /**
     * @param string $code
     * @return Currency|object
     * @throws CurrencyNotFoundException
     */
    public function getByCode(string $code): Currency
    {
        $currency = $this->getRepository()->findOneBy([
            'code' => $code
        ]);

        if ($currency === null) {
            throw new CurrencyNotFoundException('Currency with code ' . $code . ' not found.');
        }

        return $currency;
    }

    /**
     * @return Currency|object
     * @throws CurrencyNotFoundException
     */
    public function getDefault(): Currency
    {
        $currency = $this->getRepository()->findOneBy([
            'rate' => 1
        ]);

        if ($currency === null) {
            throw new CurrencyNotFoundException('Currency with rate "1" not found (should be default currency).');
        }

        return $currency;
    }

    /**
     * @param string $id
     * @return Currency
     * @throws CurrencyNotFoundException
     */
    public function get(string $id): Currency
    {
        $currency = $this->find($id);

        if ($currency === null) {
            throw new CurrencyNotFoundException('Currency with id ' . $id . ' not found.');
        }

        return $currency;
    }

    public function find(string $id): ?Currency
    {
        return $this->getQueryBuilderForAll()
            ->andWhere('c.id = :id')->setParameter('id', Uuid::fromString($id))
            ->getQuery()
            ->getOneOrNullResult();
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