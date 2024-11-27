<?php

namespace App\Tests\Service;

use App\Service\GetFileContentService;
use PHPUnit\Framework\TestCase;

class GetFileContentServiceTest extends TestCase
{
    private string $testFile;

    protected function setUp(): void
    {
        // Crée un fichier CSV temporaire pour les tests
        $this->testFile = tempnam(sys_get_temp_dir(), 'csv_test');
    }

    protected function tearDown(): void
    {
        // Supprime le fichier temporaire après chaque test
        if (file_exists($this->testFile)) {
            unlink($this->testFile);
        }
    }

    public function testGetCSVContentWithValidFile(): void
    {
        // Contenu du CSV pour le test
        $csvContent = <<<CSV
1;France;John Doe;Category A;Description 1;Code1;99.99
0;Germany;Jane Doe;Category B;Description 2;Code2;49.99
CSV;

        // Écrire le contenu dans le fichier temporaire
        file_put_contents($this->testFile, $csvContent);

        $service = new GetFileContentService();
        $result = $service->getCSVContent($this->testFile);

        // Assertions pour vérifier le contenu
        $this->assertIsArray($result);
        $this->assertCount(2, $result);

        $this->assertEquals([
            'isEuropean' => 1,
            'country' => 'France',
            'name' => 'John Doe',
            'category' => 'Category A',
            'description' => 'Description 1',
            'code' => 'Code1',
            'price' => '99.99',
        ], $result[0]);

        $this->assertEquals([
            'isEuropean' => 0,
            'country' => 'Germany',
            'name' => 'Jane Doe',
            'category' => 'Category B',
            'description' => 'Description 2',
            'code' => 'Code2',
            'price' => '49.99',
        ], $result[1]);
    }

    public function testGetCSVContentWithInvalidFile(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('File not found: /invalid/path/to/file.csv');

        $service = new GetFileContentService();
        $service->getCSVContent('/invalid/path/to/file.csv');
    }
}
