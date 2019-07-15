<?php

declare(strict_types = 1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Ergonode Migration Class:
 */
final class Version20180610062601 extends AbstractErgonodeMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE users (
                id UUID NOT NULL, 
                first_name VARCHAR(128) NOT NULL,
                last_name VARCHAR(128) NOT NULL,
                avatar_id UUID DEFAULT NULL,
                username VARCHAR(128) NOT NULL, 
                password VARCHAR(41) NOT NULL, 
                role_id UUID NOT NULL,
                roles JSON NOT NULL, 
                language VARCHAR(2) NOT NULL,
                PRIMARY KEY(id))');

        $this->addSql('CREATE UNIQUE INDEX users_username_key ON users (username)');

        $this->addSql('CREATE TABLE privileges (
                id UUID NOT NULL, 
                code VARCHAR(128) NOT NULL,
                area VARCHAR(128) NOT NULL,              
                PRIMARY KEY(id))');

        $this->addSql('CREATE UNIQUE INDEX privileges_name_key ON privileges (code)');

        $this->addSql('CREATE TABLE roles (
                id UUID NOT NULL, 
                name VARCHAR(100) NOT NULL,
                description VARCHAR(500) NOT NULL,          
                PRIMARY KEY(id))');

        $this->addSql('CREATE UNIQUE INDEX role_name_key ON roles (name)');
    }
}
