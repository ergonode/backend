<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;

final class Version20210421113000 extends AbstractErgonodeMigration
{
    /**
     * @throws \Exception
     */
    public function up(Schema $schema): void
    {
        $this->addSql(
            'INSERT INTO designer.element_type (type, variant, label, min_width, min_height, max_width, max_height)'
            .'VALUES (?, ?, ?, ?, ?, ?, ?)',
            ['PRODUCT_RELATION', 'attribute', 'Relation', 1, 1, 4, 10]
        );
    }
}
