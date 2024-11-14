<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241114111306 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Product change and add fields';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE product ADD name TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD country TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD is_european_union BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE product ALTER supplier_id DROP NOT NULL');
        $this->addSql('ALTER TABLE product ALTER description DROP NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE product DROP name');
        $this->addSql('ALTER TABLE product DROP country');
        $this->addSql('ALTER TABLE product DROP is_european_union');
        $this->addSql('ALTER TABLE product ALTER supplier_id SET NOT NULL');
        $this->addSql('ALTER TABLE product ALTER description SET NOT NULL');
    }
}
