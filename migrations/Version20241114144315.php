<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241114144315 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Product new field';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE product ADD slug TEXT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE product DROP slug');
    }
}
