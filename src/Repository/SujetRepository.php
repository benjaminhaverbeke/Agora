<?php

namespace App\Repository;

use App\Entity\Sujet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Sujet>
 */
class SujetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sujet::class);
    }

        /**
         * @return Sujet[] Returns an array of Sujet objects
         */
    public function findAllSujetsBySalon(int $id): array
    {
        return $this->createQueryBuilder('s')
            ->select('s')
            ->setParameter('id', $id)
            ->orderBy('s.id', 'ASC')
            ->andWhere('s.salon = :id')
            ->getQuery()
            ->getResult();

    }

    /**
     * @param int $id
     * @return Sujet[] Returns an array of Sujets objects
     */
    public function findSujetsWithCollections(int $id): array
    {
        return $this->createQueryBuilder('s')
            ->select('s', 'p', 'u')
            ->leftJoin('s.proposals', 'p')
            ->leftJoin('s.voters', 'u')
            ->orderBy('s.id', 'ASC')
            ->setParameter('id', $id)
            ->andWhere('s.salon = :id')
            ->getQuery()
            ->getResult();

    }

    /**
     * @param int $id
     * @return Sujet Return a Sujet with Proposals collection
     *
     */

    public function findSujetWithProposalsAndSalonUsers(int $id): Sujet
    {
        return $this->createQueryBuilder('s')
            ->select('s', 'p', 'sal', 'u', 'm', 'v')
            ->leftJoin('s.proposals', 'p')
            ->leftJoin('p.votes', 'v')
            ->leftJoin('s.salon', 'sal')
            ->leftJoin('sal.users', 'u')
            ->leftJoin('sal.messages', 'm')
            ->orderBy('s.id', 'ASC')
            ->setParameter('id', $id)
            ->andWhere('s.id = :id')
            ->getQuery()
            ->getOneOrNullResult();

    }

}
