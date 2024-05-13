<?php

namespace App\Repository;

use App\Entity\Operation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Common\Collections\ArrayCollection;

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

    public function search($criteria)
    {
        $queryBuilder = $this->createQueryBuilder('o');

        if (!empty($criteria['repertoire'])) {
            $parameters = new ArrayCollection();

            if (isset($criteria['id'])) {
                $queryBuilder->andWhere('o.id = :id');
                $parameters->set('id', $criteria['id']);
            }
            if (isset($criteria['description'])) {
                $queryBuilder->andWhere('o.description = :description');
                $parameters->set('description', $criteria['description']);
            }
            if (isset($criteria['statut'])) {
                $queryBuilder->andWhere('o.statut = :statut');
                $parameters->set('statut', $criteria['statut']);
            }
            if (isset($criteria['dateStart'])) {
                $queryBuilder->andWhere('o.dateStart = :dateStart');
                $parameters->set('dateStart', $criteria['dateStart']);
            }
            if (isset($criteria['dateEnd'])) {
                $queryBuilder->andWhere('o.dateEnd = :dateEnd');
                $parameters->set('dateEnd', $criteria['dateEnd']);
            }
            if (isset($criteria['dateForecast'])) {
                $queryBuilder->andWhere('o.dateForecast = :dateForecast');
                $parameters->set('dateForecast', $criteria['dateForecast']);
            }
            if (isset($criteria['adresse'])) {
                $queryBuilder->andWhere('o.adresse = :adresse');
                $parameters->set('adresse', $criteria['adresse']);
            }
            if (isset($criteria['client'])) {
                $queryBuilder->andWhere('o.client = :client');
                $parameters->set('client', $criteria['client']);
            }
            if (isset($criteria['type'])) {
                $queryBuilder->andWhere('o.type = :type');
                $parameters->set('type', $criteria['type']);
            }
            if (isset($criteria['user'])) {
                $queryBuilder->andWhere('o.user = :user');
                $parameters->set('user', $criteria['user']);
            }

            $queryBuilder->setParameters($parameters);
        }

        $query = $queryBuilder->getQuery();
        return $query->getResult();
    }
}
