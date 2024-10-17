<?php

namespace App\Repository;

use App\Entity\Salon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Salon>
 */
class SalonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Salon::class);
    }

    public function paginateSalons(int $userId, int $page, int $limit): Paginator
    {

        return new Paginator(
            $this->createQueryBuilder('s')
                ->leftJoin('s.users', 'u')
                ->andWhere('u.id = :userId')
                ->setParameter('userId', $userId)
                ->setFirstResult(($page - 1) * $limit)
                ->setMaxResults($limit)
                ->orderBy('s.id', 'ASC')
                ->getQuery()
                ->setHint(Paginator::HINT_ENABLE_DISTINCT, false)
        );
    }


    public function findSalonIndex(int $id): Salon
    {
        return $this->createQueryBuilder('s')
            ->select('s', 'm', 'suj', 'p', 'u')
            ->leftJoin('s.users', 'u')
            ->leftJoin('s.messages', 'm')
            ->leftJoin('s.sujets', 'suj')
            ->leftJoin('suj.proposals', 'p')
            ->andWhere('s.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();

    }

    public function findSalonWithMessages(int $id): Salon
    {
        return $this->createQueryBuilder('s')
            ->select('s', 'm')
            ->leftJoin('s.messages', 'm')
            ->andWhere('s.id =:id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();

    }

}
