<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Operation;
use App\Entity\User;

class OperationFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        
        for ($i = 0; $i < 3; $i++) {
            $user = new User();
          
            $manager->persist($user);
            
            $this->addReference('user_' . $i, $user);
        }

        // Générer des opérations et associer chaque opération à un utilisateur existant
        $operationsData = [
            ['type' => 'Type 1', 'priorite' => 'Haute', 'statut' => 'En cours'],
            ['type' => 'Type 2', 'priorite' => 'Moyenne', 'statut' => 'En cours'],
            ['type' => 'Type 3', 'priorite' => 'Basse', 'statut' => 'En cours'],
        ];

        foreach ($operationsData as $key => $operationData) {
            $operation = new Operation();
            // Set operation properties if necessary
            $operation->setUser($this->getReference('user_' . $key));
            
            $operation->setStatut($operationData['statut']);
            $manager->persist($operation);
        }

        $manager->flush();
    }
}
