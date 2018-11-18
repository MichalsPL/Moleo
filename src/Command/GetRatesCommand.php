<?php


namespace App\Command;

use App\Entity\Currency;
use App\Entity\ExchangeRateHistory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class GetRatesCommand extends Command
{

  private $container;

  public function __construct($name = null, ContainerInterface $container)
  {
    parent::__construct($name);
    $this->container = $container;
  }

  protected function configure()
  {
    $this
      ->setName('app:get-rates')
      ->setDescription('Getting current rates')
      ->setHelp('This command is getting curent rates');
  }

  protected function execute(InputInterface $input, OutputInterface $output)
  {
echo 'działą';

$xmlData = file_get_contents('http://api.nbp.pl/api/exchangerates/tables/c/');
$data=json_decode($xmlData);
var_dump($data[0]->table);
if(!$data){
  echo 'no data to import';
}else{
  foreach ($data[0]->rates as $currency_rate){
    $em = $this->container->get('doctrine')->getManager();
$currency =$em->getRepository(Currency::class, 'currency')
  ->findOneBy(['code'=>$currency_rate->code]);
dump($currency);
if(!$currency){
  $currency = new Currency();
  $currency->setName($currency_rate->currency);
  $currency->setCode($currency_rate->code);
  $em->persist($currency);
  $em->flush();
}
$exchangeRate= new ExchangeRateHistory();
$exchangeRate->setCurrency($currency);
$exchangeRate->setAskPrice($currency_rate->ask);
$exchangeRate->setBidPrice($currency_rate->bid);
$exchangeRate->setDate(new \DateTime('now'));
    $em->persist($exchangeRate);
    $em->flush();
$currency_rate->currency;
$currency_rate->code;
$currency_rate->bid;
$currency_rate->ask;
  }
}

  }
}