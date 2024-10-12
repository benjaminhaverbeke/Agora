<?php

namespace App\Repository;

use App\Entity\Vote;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
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
        return $this->createQueryBuilder('v')
            ->andWhere('v.proposal = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();


    }

    public function findVotesOnSujetByUser(int $user_id, int $sujet_id): array
    {
        return $this->createQueryBuilder('v')
            ->leftJoin('v.proposal', 'p')
            ->where('v.user = :user AND v.sujet = :sujet')
            ->setParameters(new ArrayCollection(
                array(
                    new Parameter('user', $user_id),
                    new Parameter('sujet', $sujet_id)
                )))
            ->getQuery()
            ->getResult();


    }

    public function findNotesWithoutSpecificMention(int $id, array $mentions): array
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.proposal = :id')
            ->setParameter('id', $id)
            ->andWhere('v.notes NOT IN (:mentions)')
            ->setParameter('mentions', $mentions)
            ->getQuery()
            ->getResult();
    }
}
