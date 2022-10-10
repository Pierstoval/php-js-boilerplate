<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220101000000 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
        CREATE TABLE books (
            id VARCHAR(36) NOT NULL, 
            title VARCHAR(255) NOT NULL, 
            PRIMARY KEY(id)
        )
        SQL);
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE books');
    }
}
