<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240320000000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Initial migration';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE test_entity (
            id SERIAL NOT NULL,
            name VARCHAR(255) NOT NULL,
            created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
            PRIMARY KEY(id)
        )');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE test_entity');
    }
} 