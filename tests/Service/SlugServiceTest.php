<?php

namespace App\Tests\Service;

use App\Service\SlugService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class SlugServiceTest extends KernelTestCase
{

    public function testNotHaveAccent(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $service = $container->get(SlugService::class);
        $slug = $service->slugify('Non EU Barquette de 2 pièces Bio Gros à récolter');

        $this->assertNotContains($slug, ['è', ' ', 'à']);
        $this->assertIsString($slug);
    }

    public function testHaveDash(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $service = $container->get(SlugService::class);
        $slug = $service->slugify('Non EU Barquette de 2 pièces Bio Gros');
        $this->assertIsString($slug);
        $this->assertContains($slug, ['non-eu-barquette-de-2-pieces-bio-gros']);
    }
}
