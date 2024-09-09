<?php

namespace App\Repository;

use App\Entity\Sujets;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Sujets>
 */
class SujetsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sujets::class);
    }

    //    /**
    //     * @return Sujets[] Returns an array of Sujets objects
    //     */
    public function findAllSujetsBySalon(int $id): array
    {
        return $this->createQueryBuilder('s')
            ->setParameter('id', $id)
            ->orderBy('s.id', 'ASC')
            ->leftJoin('s.salon', 'a')
            ->andWhere('a.id = :id')
            ->getQuery()
            ->getResult();

    }

//        public function findLast($value): ?Sujets
//        {
//            return $this->createQueryBuilder('s')
//                ->andWhere('s.exampleField = :val')
//                ->orderBy('s.id', 'DESC')
//                ->setParameter('val', $value)
//                ->getQuery()
//                ->getOneOrNullResult()
//            ;
//        }

    public function findLastInserted()
    {
        return $this
            ->createQueryBuilder("e")
            ->orderBy("e.id", "DESC")
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }




}
