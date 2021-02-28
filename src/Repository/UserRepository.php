<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;

class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function getPager(?string $name = null, int $page = 1, int $maxPerPage = 20): Pagerfanta
    {
        $queryBuilder = $this->createQueryBuilder('user');

        if (null !== $name) {
            $queryBuilder->andWhere($queryBuilder->expr()->orX(
                sprintf('user.firstname like \'%%%s%%\'', $name),
                sprintf('user.lastname like \'%%%s%%\'', $name)
            ));
        }

        $queryBuilder
            ->addOrderBy('user.firstname')
            ->addOrderBy('user.lastname')
        ;

        $paginator = new Pagerfanta(new QueryAdapter($queryBuilder));
        $paginator->setMaxPerPage($maxPerPage);
        $paginator->setCurrentPage($page);

        return $paginator;
    }
}
