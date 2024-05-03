<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Operation;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class OperationFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager)
    {
        // Créer des utilisateurs avec des emails factices et des mots de passe
        for ($i = 0; $i < 3; $i++) {
            $user = new User();
            $user->setEmail('user' . $i . '@example.com'); // Remplacer par de vrais emails si nécessaire
            // Génération d'un mot de passe sécurisé
            $password = 'password' . $i;
            $hashedPassword = $this->passwordHasher->hashPassword($user, $password);
            $user->setPassword($hashedPassword);
            $manager->persist($user);
            $this->addReference('user_' . $i, $user);
        }

        // Créer des opérations associées à chaque utilisateur
        $operationsData = [
            ['type' => 'Type 1', 'priorite' => 'Haute', 'statut' => 'En cours'],
            ['type' => 'Type 2', 'priorite' => 'Moyenne', 'statut' => 'En cours'],
            ['type' => 'Type 3', 'priorite' => 'Basse', 'statut' => 'En cours'],
        ];

        foreach ($operationsData as $key => $operationData) {
            $operation = new Operation();
            $operation->setUser($this->getReference('user_' . $key));
            $operation->setStatut($operationData['statut']);
            $operation->setDateForecast(new \DateTime());
            // Associer d'autres propriétés si nécessaire
            $manager->persist($operation);
        }

        $manager->flush();
    }
}
