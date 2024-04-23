<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Role;

class RoleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $apprenti = new Role();
        $apprenti->setName("Apprenti");

        $manager->persist($apprenti);

        $senior = new Role();
        $senior->setName("Senior");

        $manager->persist($senior);

        $expert = new Role();
        $expert->setName("Expert");

        $manager->persist($expert);

        $manager->flush();
    }
}
