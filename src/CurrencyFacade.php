<?php

declare(strict_types=1);

namespace Rixafy\Currency;

use Doctrine\ORM\EntityManagerInterface;

class CurrencyFacade
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var CurrencyRepository */
    private $currencyRepository;

    /** @var CurrencyFactory */
    private $currencyFactory;

    /**
     * CurrencyFacade constructor.
     * @param EntityManagerInterface $entityManager
     * @param CurrencyRepository $currencyRepository
     * @param CurrencyFactory $currencyFactory
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        CurrencyRepository $currencyRepository,
        CurrencyFactory $currencyFactory
    ) {
        $this->entityManager = $entityManager;
        $this->currencyRepository = $currencyRepository;
        $this->currencyFactory = $currencyFactory;
    }

    /**
     * @param CurrencyData $currencyData
     * @return Currency
     */
    public function create(CurrencyData $currencyData): Currency
    {
        $currency = $this->currencyFactory->create($currencyData);

        $this->entityManager->persist($currency);
        $this->entityManager->flush();

        return $currency;
    }

    /**
     * @param string $id
     * @param CurrencyData $currencyData
     * @return Currency
     * @throws Exception\CurrencyNotFoundException
     */
    public function edit(string $id, CurrencyData $currencyData): Currency
    {
        $currency = $this->currencyRepository->get($id);
        $currency->edit($currencyData);

        $this->entityManager->flush();

        return $currency;
    }

    /**
     * @param string $id
     * @return Currency
     * @throws Exception\CurrencyNotFoundException
     */
    public function get(string $id): Currency
    {
        return $this->currencyRepository->get($id);
    }

    /**
     * Permanent, removes currency from database and disk
     *
     * @param string $id
     * @throws Exception\CurrencyNotFoundException
     */
    public function remove(string $id): void
    {
        $entity = $this->get($id);

        $this->entityManager->remove($entity);

        $this->entityManager->flush();
    }
}