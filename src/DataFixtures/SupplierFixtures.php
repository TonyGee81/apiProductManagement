<?php

namespace App\DataFixtures;

use App\Entity\Supplier;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SupplierFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $suppliers = [
            'vegeland',
            'gourmandland',
        ];

        for ($i = 0; $i < count($suppliers); ++$i) {
            $supplier = new Supplier();
            $supplier
                ->setName($suppliers[$i])
            ;
            $manager->persist($supplier);
        }

        $manager->flush();
    }
}
