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
        $apprenti->setName("ROLE_JUNIOR");

        $manager->persist($apprenti);

        $senior = new Role();
        $senior->setName("ROLE_SENIOR");

        $manager->persist($senior);

        $expert = new Role();
        $expert->setName("ROLE_EXPERT");

        $manager->persist($expert);

        $manager->flush();
    }
}
