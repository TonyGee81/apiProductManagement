<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241127105537 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'USER: add news fields';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE "user" ADD first_name VARCHAR(180) DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD last_name VARCHAR(180) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE "user" DROP first_name');
        $this->addSql('ALTER TABLE "user" DROP last_name');
    }
}
