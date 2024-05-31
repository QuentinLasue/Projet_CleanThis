<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\TypeOperation;

class TypeOperationFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $grosseOpe = new TypeOperation();
        $grosseOpe->setName("Grosse Operation")
            ->setPrix(5000)
            ->setDescription("Nettoyage en profondeur de tous les espaces, y compris les surfaces, les sols et les sanitaires. Idéal pour les grands espaces commerciaux.")
            ->setPhotoOP("GrosseOP.jpg");
        $manager->persist($grosseOpe);

        $moyenneOpe = new TypeOperation();
        $moyenneOpe->setName("Moyenne Operation")
            ->setPrix(2500)
            ->setDescription("Nettoyage complet des surfaces et des sols, avec une attention particulière aux zones à fort trafic. Parfait pour les bureaux et les petits commerces.
                  ")
            ->setPhotoOP("MoyenneOP.jpg");;
        $manager->persist($moyenneOpe);

        $petiteOpe = new TypeOperation();
        $petiteOpe->setName("Petite Operation")
            ->setPrix(1000)
            ->setDescription(" Nettoyage rapide des surfaces et des sols, idéal pour les petites entreprises ou les espaces restreints nécessitant un entretien régulier.")
            ->setPhotoOP("PetiteOP.jpg");;
        $manager->persist($petiteOpe);

        $customOpe = new TypeOperation();
        $customOpe->setName("Custom Operation")
            ->setPrix(0)
            ->setDescription("Adaptée aux besoins spécifiques du client, cette option permet de choisir les services de nettoyage nécessaires pour répondre aux exigences particulières de chaque entreprise.")
            ->setPhotoOP("custom.jpg");;
        $manager->persist($customOpe);

        $manager->flush();
    }
}
