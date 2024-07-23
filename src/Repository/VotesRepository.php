<?php

namespace App\Repository;

use App\Entity\Votes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Votes>
 */
class VotesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Votes::class);
    }

    //    /**
    //     * @return Votes[] Returns an array of Votes objects
    //     */
        public function findAllVotesByProposal(int $id): array
        {
            $qb = $this->createQueryBuilder('v');


            $qb->andWhere('v.proposal = :id')
                ->setParameter('id', $id);


            return $qb->getQuery()->getResult();


        }

    //    public function findOneBySomeField($value): ?Votes
    //    {
    //        return $this->createQueryBuilder('v')
    //            ->andWhere('v.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
