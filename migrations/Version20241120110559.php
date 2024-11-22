<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241120110559 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Delete field Type';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE product DROP CONSTRAINT fk_d34a04adc54c8c93');
        $this->addSql('DROP SEQUENCE type_id_seq CASCADE');
        $this->addSql('DROP TABLE type');
        $this->addSql('DROP INDEX idx_d34a04adc54c8c93');
        $this->addSql('ALTER TABLE product DROP type_id');
    }

    public function down(Schema $schema): void
    {
    }
}
