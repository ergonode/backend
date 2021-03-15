<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;

final class Version20210309200000 extends AbstractErgonodeMigration
{
    /**
     * @throws \Exception
     */
    public function up(Schema $schema): void
    {
        $this->addSql('DROP TABLE IF EXISTS product_completeness');
        $this->addSql('
            CREATE TABLE IF NOT EXISTS product_completeness (
                product_id UUID NOT NULL,
                calculated_at TIMESTAMP WITH TIME ZONE DEFAULT NULL,
                completeness JSONB DEFAULT \'{}\',
                PRIMARY KEY(product_id)
            )
        ');

        $this->addSql('INSERT INTO product_completeness (product_id) SELECT id FROM product');
    }
}
