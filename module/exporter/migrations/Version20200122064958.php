<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;

/**
* Auto-generated Ergonode Migration Class:
*/
final class Version20200122064958 extends AbstractErgonodeMigration
{
    /**
    * @param Schema $schema
    */
    public function up(Schema $schema) : void
    {
        $this->addSql('CREATE SCHEMA IF NOT EXISTS exporter');
        $this->addSql(
            'CREATE TABLE exporter.product(
                    id uuid NOT NULL,
                    data jsonb NOT NULL,
                    PRIMARY KEY (id)
                 )'
        );

        $this->addSql(
            'CREATE TABLE exporter.category(
                    id uuid NOT NULL,
                    data jsonb NOT NULL,
                    PRIMARY KEY (id)
                 )'
        );

        $this->addSql(
            'CREATE TABLE exporter.attribute(
                    id uuid NOT NULL,
                    data jsonb NOT NULL,
                    PRIMARY KEY (id)
                 )'
        );

        $this->addSql('
            CREATE TABLE exporter.export_profile (
                id UUID NOT NULL,              
                type VARCHAR(255) NOT NULL,                
                name VARCHAR(255) NOT NULL,                
                configuration JSON NOT NULL,                
                created_at TIMESTAMP WITHOUT TIME ZONE NOT NULL,
                updated_at TIMESTAMP WITHOUT TIME ZONE NOT NULL,               
                PRIMARY KEY(id)
            )
        ');
    }
}
