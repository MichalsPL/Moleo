<?php

namespace App\Command;

use App\Entity\Currency;
use App\Entity\ExchangeRateHistory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class AbstractCommand extends Command
{
  protected $em;

  protected $container;

  public function __construct($name = null, ContainerInterface $container)
  {
    parent::__construct($name);
    $this->container = $container;
    $this->em = $container->get('doctrine')->getManager();
  }

  protected function configure()
  {
    $reflect = new \ReflectionClass($this);
    $className = $reflect->getShortName();
    $this
      ->setName('get:' . $className)
      ->setDescription('Getting current rates for ' . $className)
      ->setHelp('This command is getting curent rates for class' . $className);
  }

  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $rawData = $this->fetchData();
    if (!$rawData) {
      echo 'no data to fetch';
      die;
    }
    $preparedData = $this->prepareData($rawData);
    $this->saveData($preparedData);


  }

  abstract protected function fetchData(): array;

  abstract protected function prepareData(array $rawData): array;

  protected function saveData(array $data)
  {
    foreach ($data as $currencyData) {
      $currency = $this->prepareCurrency($currencyData);
      $this->addExchangeRate($currency, $currencyData);
    }
  }

  protected function prepareCurrency(array $currencyData): Currency
  {
    $currency = $this->em->getRepository(Currency::class, 'currency')
      ->findOneBy(['code' => $currencyData['code']]);
    if (!$currency) {
      $currency = new Currency();
      $currency->setName($currencyData['name']);
      $currency->setCode($currencyData['code']);
      $this->em->persist($currency);
      $this->em->flush();
    }
    return $currency;
  }

  protected function addExchangeRate(Currency $currency, array $currencyData)
  {
    $exchangeRate = new ExchangeRateHistory();
    $exchangeRate->setCurrency($currency);
    if ($currencyData['ask']) {
      $exchangeRate->setAskPrice($currencyData['ask']);
    }
    if ($currencyData['bid']) {
      $exchangeRate->setBidPrice($currencyData['bid']);
    }
    if ($currencyData['mid']) {
      $exchangeRate->setMidPrice($currencyData['mid']);
    }
    $exchangeRate->setDate(new \DateTime('now'));
    $this->em->persist($exchangeRate);
    $this->em->flush();
  }


}