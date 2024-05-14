<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Operation;
use App\Entity\User;

class OperationFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // Créer des utilisateurs avec des emails factices et des mots de passe
        for ($i = 0; $i < 3; $i++) {
            $user = new User();
            $user->setEmail('user' . $i . '@example.com'); // Remplacer par de vrais emails si nécessaire
            // Notez que le mot de passe n'est pas hashé ici
            $user->setPassword('password' . $i);
            $manager->persist($user);
            $this->addReference('user_' . $i, $user);
        }

        // Créer des opérations associées à chaque utilisateur
        $operationsData = [
            ['description' => 'Description de l\'opération 1', 'statut' => 'En cours'],
            ['description' => 'Description de l\'opération 2', 'statut' => 'En cours'],
            ['description' => 'Description de l\'opération 3', 'statut' => 'En cours'],
        ];

        foreach ($operationsData as $key => $operationData) {
            $operation = new Operation();
            $operation->setDescription($operationData['description']);
            $operation->setStatut($operationData['statut']);
            $operation->setDateForecast(new \DateTime());
            // Associer d'autres propriétés si nécessaire
            $manager->persist($operation);
        }

        $manager->flush();
    }
}
