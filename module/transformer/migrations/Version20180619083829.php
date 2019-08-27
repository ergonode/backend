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
final class Version20180619083829 extends AbstractErgonodeMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA IF NOT EXISTS importer');
        $this->addSql(
            'CREATE TABLE importer.transformer (
                      id UUID NOT NULL, 
                      name VARCHAR(128) NOT NULL, 
                      key VARCHAR(128) NOT NULL,
                      PRIMARY KEY(id)
                )'
        );
        $this->addSql('CREATE TABLE importer.transformer_converter (id UUID NOT NULL, transformer_id UUID NOT NULL, field VARCHAR(64) NOT NULL, type VARCHAR(255) NOT NULL, options JSON NOT NULL, PRIMARY KEY(id))');
        $this->addSql(
            'CREATE TABLE importer.processor (
                    id UUID NOT NULL, 
                    import_id UUID NOT NULL, 
                    transformer_Id UUID NOT NULL, 
                    action VARCHAR(64) NOT NULL,
                    created_at TIMESTAMP WITHOUT TIME ZONE NOT NULL,
                    updated_at TIMESTAMP WITHOUT TIME ZONE NOT NULL,
                    started_at TIMESTAMP WITHOUT TIME ZONE,
                    ended_at TIMESTAMP WITHOUT TIME ZONE, 
                    status character varying(32) NOT NULL, 
                    PRIMARY KEY(id)
                )'
        );
    }
}
