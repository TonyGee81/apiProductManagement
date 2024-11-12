<?php

namespace App\DataFixtures;

use App\Entity\Supplier;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $supplier = [
            'vegeland',
            'gourmandland'
        ];

        for ($i = 0; $i < count($supplier) ; $i++) {
            $product = new Supplier();
            $product
                ->setName($supplier[$i])
            ;
            $manager->persist($product);
        }

        $manager->flush();
    }
}