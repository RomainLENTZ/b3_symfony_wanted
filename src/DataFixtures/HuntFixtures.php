<?php

namespace App\DataFixtures;

use App\Entity\Hunt;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class HuntFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 30; $i++){
            $hunt = new Hunt();
            $hunt->setName("Wanted Notice ".$i);
            $hunt->setBounty(rand(100,2000000));
            $manager->persist($hunt);
        }

        $manager->flush();
    }
}