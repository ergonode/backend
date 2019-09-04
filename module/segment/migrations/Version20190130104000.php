<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;
use Ramsey\Uuid\Uuid;

/**
 * Auto-generated Ergonode Migration Class:
 */
final class Version20190130104000 extends AbstractErgonodeMigration
{
    /**
     * @param Schema $schema
     *
     * @throws \Exception
     */
    public function up(Schema $schema): void
    {
        $this->addSql(
            'CREATE TABLE segment (
                    id UUID NOT NULL,
                    code VARCHAR(100) NOT NULL,
                    name JSON NOT NULL,
                    description JSON NOT NULL,
                    status VARCHAR(32) NOT NULL,            
                    PRIMARY KEY(id)
                )'
        );

        $this->addSql('INSERT INTO privileges (id, code, area) VALUES (?, ?, ?)', [Uuid::uuid4()->toString(), 'SEGMENT_CREATE', 'Segment']);
        $this->addSql('INSERT INTO privileges (id, code, area) VALUES (?, ?, ?)', [Uuid::uuid4()->toString(), 'SEGMENT_READ', 'Segment']);
        $this->addSql('INSERT INTO privileges (id, code, area) VALUES (?, ?, ?)', [Uuid::uuid4()->toString(), 'SEGMENT_UPDATE', 'Segment']);
        $this->addSql('INSERT INTO privileges (id, code, area) VALUES (?, ?, ?)', [Uuid::uuid4()->toString(), 'SEGMENT_DELETE', 'Segment']);
    }
}
