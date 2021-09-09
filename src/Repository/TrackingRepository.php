<?php

namespace App\Repository;

use App\Entity\Tracking;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;

/**
 * @method Tracking|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tracking|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tracking[]    findAll()
 * @method Tracking[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrackingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tracking::class);
    }

    public function ipAlreadyTracked(string $ip): bool
    {
        $queryBuilder = $this
            ->createQueryBuilder('tracking')
            ->where('tracking.ip = :ip')
            ->setParameter('ip', $ip)
        ;

        return count($queryBuilder->getQuery()->getResult()) > 0;
    }

    public function getPager(int $page = 1, int $maxPerPage = 20): Pagerfanta
    {
        $queryBuilder = $this->createQueryBuilder('tracking');

        $queryBuilder->orderBy('tracking.date', 'DESC');

        $paginator = new Pagerfanta(new QueryAdapter($queryBuilder));
        $paginator->setMaxPerPage($maxPerPage);
        $paginator->setCurrentPage($page);

        return $paginator;
    }

    public function clearTracking(): int
    {
        return $this->createQueryBuilder('tracking')
            ->delete()
            ->getQuery()
            ->execute()
        ;
    }
}
