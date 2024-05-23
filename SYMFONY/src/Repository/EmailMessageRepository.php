<?php

namespace App\Repository;

use App\Entity\EmailMessage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EmailMessage>
 *
 * @method EmailMessage|null find($id, $lockMode = null, $lockVersion = null)
 * @method EmailMessage|null findOneBy(array $criteria, array $orderBy = null)
 * @method EmailMessage[]    findAll()
 * @method EmailMessage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmailMessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EmailMessage::class);
    }

    //    /**
    //     * @return EmailMessage[] Returns an array of EmailMessage objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('e.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?EmailMessage
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
