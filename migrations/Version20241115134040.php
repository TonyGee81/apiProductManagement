<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241115134040 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add slug field';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE supplier ADD slug TEXT');
        $this->addSql('ALTER TABLE type ADD slug TEXT');
        $this->addSql('UPDATE supplier SET slug = (SELECT name) WHERE slug IS NULL;');
        $this->addSql('UPDATE type SET slug = (SELECT name) WHERE slug IS NULL;');
        $this->addSql('ALTER TABLE supplier ALTER COLUMN slug SET NOT NULL');
        $this->addSql('ALTER TABLE type ALTER COLUMN slug SET NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE type DROP slug');
        $this->addSql('ALTER TABLE supplier DROP slug');
    }
}
