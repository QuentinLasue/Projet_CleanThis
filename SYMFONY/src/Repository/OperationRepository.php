<?php

namespace App\Repository;

use App\Entity\Operation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class OperationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Operation::class);
    }

    // Méthode pour rechercher par critère 1 (dans ce cas, l'ID)
    public function findByCritere1($id)
    {
        return $this->findOneBy(['id' => $id]);
    }

    // Méthode pour rechercher par statut
    public function findByCritereStatut($statut)
    {
        return $this->findBy(['statut' => $statut]);
    }

    // Méthode pour rechercher par champ spécifié et terme
    public function findByFieldAndTerm($field, $term)
    {
        return $this->createQueryBuilder('operation')
            ->leftJoin('operation.user', 'user') // Assurez-vous que la relation avec l'utilisateur est correctement définie
            ->andWhere("operation.$field = :term") // Utilisez le bon champ pour la condition
            ->orWhere("user.name = :term") // Ou utilisez le nom de l'utilisateur comme critère de recherche
            ->setParameter('term', $term)
            ->getQuery()
            ->getResult();
    }
}
