<?php

namespace App\Repository;

use App\Entity\Vote;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Vote>
 */
class VoteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vote::class);
    }

    //    /**
    //     * @return Vote[] Returns an array of Vote objects
    //     */
        public function findAllVotesByProposal(int $id): array
        {
            $qb = $this->createQueryBuilder('v');


            $qb->andWhere('v.proposal = :id')
                ->setParameter('id', $id);


            return $qb->getQuery()->getResult();


        }

        public function findNotesWithoutSpecificMention(int $id, array $mentions): array
        {
            return $this->createQueryBuilder('v')
                ->andWhere('v.proposal = :id')
                ->setParameter('id', $id)
                ->andWhere('v.notes NOT IN (:mentions)')
                ->setParameter('mentions', $mentions)
                ->getQuery()
                ->getResult()
            ;
        }
}
