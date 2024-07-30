<?php

namespace App\Repository;

use App\Entity\Salons;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\User;

/**
 * @extends ServiceEntityRepository<Salons>
 */
class SalonsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Salons::class);
    }


//        /**
//         * @return Salons[] Returns an array of Salons objects
//         */
//        public function findByUserField(User $user): array
//        {
//            return $this->createQueryBuilder('s')
//                ->andWhere('s.user = :val')
//                ->setParameter('val', $value)
//                ->orderBy('s.id', 'ASC')
//                ->setMaxResults(10)
//                ->getQuery()
//                ->getResult()
//            ;
//        }

    //    public function findOneBySomeField($value): ?Salons
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
