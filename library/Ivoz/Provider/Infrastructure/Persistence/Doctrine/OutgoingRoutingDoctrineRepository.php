<?php

namespace Ivoz\Provider\Infrastructure\Persistence\Doctrine;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Ivoz\Provider\Domain\Model\OutgoingRouting\OutgoingRoutingRepository;
use Ivoz\Provider\Domain\Model\OutgoingRouting\OutgoingRouting;
use Ivoz\Provider\Domain\Model\RoutingPattern\RoutingPatternInterface;
use Ivoz\Provider\Domain\Model\RoutingPatternGroup\RoutingPatternGroupInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * OutgoingRoutingDoctrineRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class OutgoingRoutingDoctrineRepository extends ServiceEntityRepository implements OutgoingRoutingRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, OutgoingRouting::class);
    }

    /*
     * Finds outgoing routings using the given pattern or a group having the given pattern
     */
    public function findByRoutingPattern(RoutingPatternInterface $routingPattern) :array
    {
        $qb = $this->createQueryBuilder('self');
        $query = $qb
            ->select('self')
            ->innerJoin('self.routingPatternGroup', 'routingPatternGroup')
            ->innerJoin('routingPatternGroup.relPatterns', 'relPattern')
            ->where('relPattern.routingPattern = :routingPatternId')
            ->setParameter(':routingPatternId', $routingPattern->getId())
            ->groupBy('self.id')
            ->getQuery();

        return array_merge(
            $routingPattern->getOutgoingRoutings(),
            $query->getResult()
        );
    }
}
