<?php

namespace App\Repository\Tmdb;

use App\Entity\Tmdb\BuildProcess;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class BuildProcessRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BuildProcess::class);
    }

    public function isProcessPending(): bool
    {
        $queryBuilder = $this->createQueryBuilder('buildProcess')
            ->where('buildProcess.endedAt is null')
        ;

        return count($queryBuilder->getQuery()->getResult()) > 0;
    }

    public function findPendingProcess(): ?BuildProcess
    {
        $queryBuilder = $this->createQueryBuilder('buildProcess')
            ->where('buildProcess.endedAt is null')
        ;

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }

    public function findPendingProcessByType(string $type): ?BuildProcess
    {
        $queryBuilder = $this->createQueryBuilder('buildProcess')
            ->andWhere('buildProcess.endedAt is null')
            ->andWhere('buildProcess.type = :type')
            ->setParameter('type', $type)
        ;

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }
}
