<?php

namespace App\Repository;

use App\Entity\Salons;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;

/**
 * @extends ServiceEntityRepository<Salons>
 */
class SalonsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Salons::class);
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
                ->getQuery()
                ->setHint(Paginator::HINT_ENABLE_DISTINCT, false)
        );
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
