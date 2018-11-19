<?php

namespace App\Repository;

use App\Entity\Currency;
use App\Entity\ExchangeRateHistory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ExchangeRateHistory|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExchangeRateHistory|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExchangeRateHistory[]    findAll()
 * @method ExchangeRateHistory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExchangeRateHistoryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ExchangeRateHistory::class);
    }


}
