<?php

namespace App\Repository;

use App\Entity\Proposal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Proposal>
 */
class ProposalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Proposal::class);
    }

    //    /**
    //     * @return Proposal[] Returns an array of Proposal objects
    //     */
        public function allPropositionSujet(int $id): array
        {
            return $this->createQueryBuilder('p')
                ->andWhere('p.sujet = :id')
                ->setParameter('id', $id)
                ->orderBy('p.id', 'ASC')
                ->getQuery()
                ->getResult();

        }

    //    public function findOneBySomeField($value): ?Proposal
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
