<?php

declare(strict_types=1);

namespace Rixafy\Currency;

use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\UuidInterface;

class CurrencyFacade
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var CurrencyRepository */
    private $currencyRepository;

    /** @var CurrencyFactory */
    private $currencyFactory;

    public function __construct(
        EntityManagerInterface $entityManager,
        CurrencyRepository $currencyRepository,
        CurrencyFactory $currencyFactory
    ) {
        $this->entityManager = $entityManager;
        $this->currencyRepository = $currencyRepository;
        $this->currencyFactory = $currencyFactory;
    }

    public function create(CurrencyData $currencyData): Currency
    {
        $currency = $this->currencyFactory->create($currencyData);

        $this->entityManager->persist($currency);
        $this->entityManager->flush();

        return $currency;
    }

    /**
     * @throws Exception\CurrencyNotFoundException
     */
    public function edit(UuidInterface $id, CurrencyData $currencyData): Currency
    {
        $currency = $this->currencyRepository->get($id);
        $currency->edit($currencyData);

        $this->entityManager->flush();

        return $currency;
    }

    /**
     * @throws Exception\CurrencyNotFoundException
     */
    public function get(UuidInterface $id): Currency
    {
        return $this->currencyRepository->get($id);
    }

    /**
     * Permanent, removes currency from database and disk
     *
     * @throws Exception\CurrencyNotFoundException
     */
    public function remove(UuidInterface $id): void
    {
        $entity = $this->get($id);

        $this->entityManager->remove($entity);

        $this->entityManager->flush();
    }

    /**
     * @throws Exception\CurrencyNotFoundException
     */
    public function getByCode(string $code): Currency
    {
        return $this->currencyRepository->getByCode($code);
    }

    /**
     * @throws Exception\CurrencyNotFoundException
     */
    public function getDefault(): Currency
    {
        return $this->currencyRepository->getDefault();
    }

    /**
     * @return Currency[]
     */
    public function getAll(): array
    {
        return $this->currencyRepository->getQueryBuilderForAll()->getQuery()->getResult();
    }

    /**
     * @return Currency[]
     */
    public function getAllActive(): array
    {
        return $this->currencyRepository->getQueryBuilderForAllActive()->getQuery()->getResult();
    }
}
