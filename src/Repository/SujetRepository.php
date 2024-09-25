<?php

namespace App\Repository;

use App\Entity\Sujet;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
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

    //    /**
    //     * @return Sujet[] Returns an array of Sujet objects
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

//        public function findLast($value): ?Sujet
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

    public function findSujetCollection(int $id): ?Sujet
    {
        return $this->createQueryBuilder('s')
            ->leftJoin('s.salon', 'a')
            ->leftJoin('a.messages', 'm')
            ->leftJoin('a.user', 'c')
            ->andWhere('s.id = :id')
            ->orderBy('s.id', 'DESC')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();


    }

    public function findSujetsVoted(int $userId, int $sujetId): array
    {
        return $this->createQueryBuilder('s')

            ->leftJoin('s.voters', 'u')
            ->where('s.id = :sujet_id AND u.id = :user_id')
            ->setParameters(new ArrayCollection((array(
                new Parameter('sujet_id', $sujetId),
                new Parameter('user_id', $userId)
            ))))
            ->getQuery()
            ->getResult();

    }




}
