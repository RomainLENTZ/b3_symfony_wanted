<?php

namespace App\DataFixtures;


use App\Entity\Target;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TargetFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 30; $i++) {
            $target = new Target();
            $target->setName("Maximator-" . $i);
            $target->setDescription("Un tueur terrifiant + de 5500 meurtres");
            $manager->persist($target);
        }

        $manager->flush();
    }
}