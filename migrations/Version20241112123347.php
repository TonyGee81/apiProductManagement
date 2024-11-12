<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241112123347 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Edit Product to create type_id field';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE product ADD type_id VARCHAR(255)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADC54C8C93 FOREIGN KEY (type_id) REFERENCES type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_D34A04ADC54C8C93 ON product (type_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE product DROP CONSTRAINT FK_D34A04ADC54C8C93');
        $this->addSql('DROP INDEX IDX_D34A04ADC54C8C93');
        $this->addSql('ALTER TABLE product DROP type_id');
    }
}
