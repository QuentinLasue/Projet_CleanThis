<?php 

namespace App\Repository;

use App\Entity\Operation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

class OperationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Operation::class);
    }

    public function findByCritereId($id)
    {
        return $this->findBy(['id' => $id]);
    }


    //    public function findOneBySomeField($value): ?Operation
    //    {
    //        return $this->createQueryBuilder('o')
    //            ->andWhere('o.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
    //Fonction pagination et liste opération 'En attente'
    public function paginateOperationWait(int $page, int $limit): Paginator
    {
        return new Paginator($this
            ->createQueryBuilder('r')
            ->where('r.statut = :en_attente')// ajout de la condition 
            ->setParameter('en_attente','En Attente') // initialisation du paramètre 
            ->setFirstResult(($page -1) * $limit) // spécifie l'offset
            ->setMaxResults($limit) // nombre maximum de resulstat par page
            ->getQuery() // convertit en objet query 
            ->setHint(Paginator::HINT_ENABLE_DISTINCT,false)  // passage d'info a l'objet query pour dire que on n'a pas besoin du distinct
        );
    }

    public function findByCritereStatut($statut)
{
    return $this->createQueryBuilder('operation')
        ->andWhere('operation.statut = :statut')
        ->setParameter('statut', $statut)
        ->getQuery()
        ->getResult();

}
public function findByCritereDatePrev($dateForecast)
{
    return $this->createQueryBuilder('operation')
        ->andWhere('operation.dateForecast = :dateForecast')
        ->setParameter('dateForecast', new \DateTime($dateForecast)) // Conversion en DateTime
        ->getQuery()
        ->getResult();
}

public function findByCritereClient($client)
{
    dump($client);
    return $this->createQueryBuilder('operation')
        ->join('operation.client', 'client')
        ->andWhere('client.name LIKE :clientName OR client.firstname LIKE :clientName')
        ->setParameter('clientName', '%' . $client . '%')
        ->getQuery()
        ->getResult();
}

public function findByCritereUser($user)
{
    return $this->createQueryBuilder('operation')
        ->join('operation.user', 'user')
        ->andWhere('user.name LIKE :userName OR user.firstname LIKE :userName')
        ->setParameter('userName', '%' . $user . '%')
        ->getQuery()
        ->getResult();
}

public function findByFieldAndTerm($field, $term)
{
    return $this->createQueryBuilder('operation')
        ->leftJoin('operation.user', 'user')
        ->leftJoin('operation.client', 'client')
        ->andWhere("operation.$field LIKE :term")
        ->orWhere("user.name LIKE :term")
        ->orWhere("user.firstname LIKE :term")
        ->orWhere("client.name LIKE :term")
        ->orWhere("client.firstname LIKE :term")
        ->setParameter('term', '%' . $term . '%')
        ->getQuery()
        ->getResult();
}
}