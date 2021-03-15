<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;

final class Version20200811070000 extends AbstractErgonodeMigration
{
    /**
     * @throws \Exception
     */
    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE IF NOT EXISTS product_completeness (
                product_id UUID NOT NULL,
                attribute_id UUID NOT NULL,
                template_id UUID NOT NULL,
                language VARCHAR(5) NOT NULL,
                required boolean NOT NULL,
                filled boolean NOT NULL,
                PRIMARY KEY(product_id, attribute_id, language)
            )
        ');
    }
}
