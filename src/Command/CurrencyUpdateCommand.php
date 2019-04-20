<?php

declare(strict_types=1);

namespace Rixafy\Currency\Command;

use Doctrine\ORM\EntityManagerInterface;
use Nette\Utils\Json;
use Nette\Utils\JsonException;
use Rixafy\Currency\CurrencyConfig;
use Rixafy\Currency\CurrencyData;
use Rixafy\Currency\CurrencyFacade;
use Rixafy\Currency\CurrencyFactory;
use Rixafy\Currency\Exception\CurrencyNotFoundException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class CurrencyUpdateCommand extends Command
{
    /** @var CurrencyConfig */
    public $currencyConfig;

    /** @var CurrencyFacade */
    public $currencyFacade;

    /** @var CurrencyFactory */
    public $currencyFactory;

    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var string */
    public static $defaultName = 'rixafy:currency:update';

    public function __construct(CurrencyConfig $currencyConfig, CurrencyFacade $currencyFacade, CurrencyFactory $currencyFactory, EntityManagerInterface $entityManager)
    {
        $this->currencyConfig = $currencyConfig;
        $this->currencyFacade = $currencyFacade;
        $this->currencyFactory = $currencyFactory;
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    public function execute(InputInterface $input, OutputInterface $output): void
    {
        $content = @file_get_contents('http://data.fixer.io/api/latest?access_key=' . $this->currencyConfig->getApiKey() . '&base=' . $this->currencyConfig->getBaseCurrency());
        if ($content !== false) {
            try {
                $json = Json::decode($content);

                if ($json['success']) {
                    try {
                        $baseCurrency = $this->currencyFacade->getByCode($json['base']);
                        $baseCurrency->updateRate(1);

                    } catch (CurrencyNotFoundException $e) {
                        $data = new CurrencyData();

                        $data->rate = 1;
                        $data->code = $json['base'];

                        $baseCurrency = $this->currencyFactory->create($data);

                        $this->entityManager->persist($baseCurrency);
                    }

                    foreach ($json['rates'] as $code => $rate) {
                        try {
                            $currency = $this->currencyFacade->getByCode($code);
                            $currency->updateRate($rate);

                        } catch (CurrencyNotFoundException $e) {
                            $data = new CurrencyData();

                            $data->code = $code;
                            $data->rate = $rate;

                            $currency = $this->currencyFactory->create($data);
                        }

                        $this->entityManager->persist($currency);
                    }

                    $this->entityManager->flush();

                    $output->writeln('<fg=green;options=bold></>');
                    $output->writeln('<fg=green;options=bold>Currencies successfully updated! ('.count($json['rates']).')</>');
                    $output->writeln('<fg=green;options=bold></>');

                } else {
                    $output->writeln('<fg=red;options=bold>Read from fixer.io failed (json field success is negative), check your api key!</>');
                }

            } catch (JsonException $e) {
                $output->writeln('<fg=red;options=bold>Read from fixer.io failed (JsonException "' . $e->getMessage() . '"), check your api key!</>');
            }
        }
    }
}
