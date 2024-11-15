<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Service\SlugService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{

    public function __construct(private SlugService $slugService){}

    public function load(
        ObjectManager $manager
    ): void
    {
        $categories = [
            'Crème fraîche',
            'Lait fermenté',
            'Lait',
            'Beurre',
            'Ricotta',
            'Yaourt',
            'Fromage',
            'Kéfir',
        ];

        for ($i = 0; $i < count($categories); ++$i) {
            $category = new Category();
            $category
                ->setSlug($this->slugService->slugify($categories[$i]))
                ->setName($categories[$i])
            ;
            $manager->persist($category);
        }

        $manager->flush();
    }
}
