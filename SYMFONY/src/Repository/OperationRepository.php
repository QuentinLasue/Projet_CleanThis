<?php

namespace App\Repository;

use App\Entity\Operation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Operation>
 *
 * @method Operation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Operation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Operation[]    findAll()
 * @method Operation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
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
}
