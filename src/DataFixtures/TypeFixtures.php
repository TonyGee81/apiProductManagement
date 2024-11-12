<?php

namespace App\DataFixtures;

use App\Entity\Type;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TypeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $types = [
            'légume',
            'fruit'
        ];

        for ($i = 0; $i < count($types) ; $i++) {
            $type = new Type();
            $type
                ->setName($types[$i])
            ;
            $manager->persist($type);
        }

        $manager->flush();
    }
}