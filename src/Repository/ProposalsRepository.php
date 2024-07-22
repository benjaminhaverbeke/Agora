<?php

namespace App\Repository;

use App\Entity\Proposals;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Proposals>
 */
class ProposalsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Proposals::class);
    }

    //    /**
    //     * @return Proposals[] Returns an array of Proposals objects
    //     */
        public function AllPropositionSujet(int $id): array
        {
            return $this->createQueryBuilder('p')
                ->andWhere('p.sujet = :id')
                ->setParameter('id', $id)
                ->orderBy('p.id', 'ASC')
                ->getQuery()
                ->getResult();

        }

    //    public function findOneBySomeField($value): ?Proposals
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
