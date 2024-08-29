<?php

namespace App\Repository;

use App\Entity\Invitation;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


/**
 * @extends ServiceEntityRepository<Invitation>
 */
class InvitationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Invitation::class);
    }

    /**
     * @return Invitation[] Returns an array of Invitation objects
     */
    public function findByReceiverField(User $receiver): array
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.receiver = :receiver')
            ->setParameter('receiver', $receiver)
            ->orderBy('i.createdAt', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findOneByReceiver($receiver): ?Invitation
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
